<?php

use Doctrine\Common\DataFixtures\ReferenceRepository;

class HomepageScenarioTest extends \Liip\FunctionalTestBundle\Test\WebTestCase
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

    public function testHomepage()
    {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/');

        $this->assertStatusCode(200, $client);

        $this->assertSame(1, $crawler->filter('html:contains("John Doe - user@exemple.org")')->count());
        $this->assertSame(0, $crawler->filter('html:contains("Jane Doe - admin@exemple.org")')->count());

        $this->loginAs($this->fixtures->getReference('user'), 'main');
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/');
        $this->assertSame(1, $crawler->filter('ul li strong:contains("John Doe - user@exemple.org")')->count());
    }
}
