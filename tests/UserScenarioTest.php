<?php

class UserScenarioTest extends \Liip\FunctionalTestBundle\Test\WebTestCase
{
    /** @var ReferenceRepository */
    private $fixtures;

    protected function setUp()
    {
        $this->fixtures = $this->loadFixtures([
            \App\DataFixtures\ORM\LoadUser::class,
            \App\DataFixtures\ORM\LoadAdmin::class,
        ])->getReferenceRepository();
    }

    public function testMyProfileAsNonAuthentificated()
    {
        $client = $this->makeClient();
        $client->request('GET', '/my_profile');
        $this->assertStatusCode(302, $client);
        $client->followRedirect();
        $this->assertSame('http://localhost/login', $client->getRequest()->getUri());
    }

    public function testMyProfile()
    {
        $this->loginAs($this->fixtures->getReference('user'), 'main');
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/my_profile');
        $this->assertStatusCode(200, $client);

        $this->assertSame(1, $crawler->filter('li:contains("John")')->count());
        $this->assertSame(1, $crawler->filter('li:contains("Doe")')->count());
        $this->assertSame(1, $crawler->filter('li:contains("user@exemple.org")')->count());
        $this->assertSame(1, $crawler->filter('li:contains("January 1, 1990 00:00")')->count());
    }

    public function testUserProfileAsNonAuthentificated()
    {
        $client = $this->makeClient();
        $client->request('GET', '/profile/1');
        $this->assertStatusCode(302, $client);
        $client->followRedirect();
        $this->assertSame('http://localhost/login', $client->getRequest()->getUri());
    }

    public function testUserProfileCanRedirectToMine()
    {
        $this->loginAs($this->fixtures->getReference('user'), 'main');
        $client = $this->makeClient();
        $client->request('GET', '/profile/1');
        $this->assertStatusCode(302, $client);
        $client->followRedirect();
        $this->assertSame('http://localhost/my_profile', $client->getRequest()->getUri());
    }

    public function testUserProfile()
    {
        $this->loginAs($this->fixtures->getReference('admin'), 'main');
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/profile/1');
        $this->assertStatusCode(200, $client);

        $this->assertSame(1, $crawler->filter('li:contains("John")')->count());
        $this->assertSame(1, $crawler->filter('li:contains("Doe")')->count());
        $this->assertSame(1, $crawler->filter('li:contains("user@exemple.org")')->count());
        $this->assertSame(1, $crawler->filter('li:contains("January 1, 1990 00:00")')->count());
    }
}
