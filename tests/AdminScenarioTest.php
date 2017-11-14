<?php

class AdminScenarioTest extends \Liip\FunctionalTestBundle\Test\WebTestCase
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

    public function testAdminIsProtected()
    {
        $client = $this->makeClient();
        $client->request('GET', '/admin');

        $this->assertStatusCode(302, $client);
        $client->followRedirect();
        $this->assertSame('http://localhost/login', $client->getRequest()->getUri());

        $client = $this->makeClient();

        $this->loginAs($this->fixtures->getReference('user'), 'main');
        $client->request('GET', '/admin');
        $this->assertStatusCode(302, $client);
        $client->followRedirect();
        $this->assertSame('http://localhost/login', $client->getRequest()->getUri());
    }

    public function testAdminDashboard()
    {
        $this->loginAs($this->fixtures->getReference('admin'), 'main');
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/admin');

        $this->assertStatusCode(200, $client);

        $this->assertSame(1, $crawler->filter('html:contains("John Doe - user@exemple.org")')->count());
        $this->assertSame(0, $crawler->filter('html:contains("Jane Doe - admin@exemple.org")')->count());

        $uri = $crawler->selectLink('delete')->link()->getUri();
        $client->request('GET', $uri);
        $this->assertStatusCode(302, $client);
        $crawler = $client->followRedirect();

        $this->assertSame(0, $crawler->filter('html:contains("John Doe - user@exemple.org")')->count());
    }
}
