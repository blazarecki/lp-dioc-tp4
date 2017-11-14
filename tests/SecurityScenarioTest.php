<?php

use Doctrine\Common\DataFixtures\ReferenceRepository;

class SecurityScenarioTest extends \Liip\FunctionalTestBundle\Test\WebTestCase
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

    public static function userProvider()
    {
        return [
            ['user@exemple.org', 'user'],
            ['admin@exemple.org', 'admin'],
        ];
    }

    /**
     * @dataProvider userProvider
     */
    public function testLogin($email = 'user@exemple.org', $password = 'user')
    {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/login');

        $this->assertStatusCode(200, $client);

        $form = $crawler->selectButton('login')->form();
        $form->setValues(['_username' => $email, '_password' => $password]);
        $client->submit($form);

        $this->assertStatusCode(302, $client);

        $client->followRedirect();
        $this->assertStatusCode(200, $client);
        $this->assertSame('http://localhost/my_profile', $client->getRequest()->getUri());
    }

    public function testLogout()
    {
        $this->loginAs($this->fixtures->getReference('user'), 'main');
        $client = $this->makeClient();

        $client->request('GET', '/logout');
        $this->assertStatusCode(302, $client);
    }

    public function testRegister()
    {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/register');

        $this->assertStatusCode(200, $client);

        $form = $crawler->selectButton('register')->form();
        $form->setValues([
            'user[firstname]' => 'Foo',
            'user[lastname]' => 'Bar',
            'user[email]' => $email = 'foo@exemple.org',
            'user[plainPassword][first]' => $password = 'password',
            'user[plainPassword][second]' => 'password',
            'user[birthday][year]' => '1990',
            'user[birthday][month]' => '10',
            'user[birthday][day]' => '1',
        ]);
        $client->submit($form);

        $this->assertStatusCode(302, $client);

        $client->followRedirect();
        $this->assertStatusCode(200, $client);
        $this->assertSame('http://localhost/login', $client->getRequest()->getUri());

        $this->testLogin($email, $password);
    }
}
