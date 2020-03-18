<?php

namespace App\Tests\Controller;

use App\Tests\HelperLoginTrait;
use App\DataFixtures\AppFixtures;
use Symfony\Component\HttpFoundation\Response;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserListControllerTest extends WebTestCase
{
    use FixturesTrait;
    use HelperLoginTrait;

    /**
     * @var string
     */
    private $route;

    public function setUp()
    {
        $this->route = '/users';
    }

    public function testRedirectToLogin()
    {
        $client = static::createClient();
        $client->request('GET', $this->route);
        $this->assertResponseRedirects('/login');
    }

    public function testAccessWithAdmin()
    {
        $this->loadFixtures([AppFixtures::class]);
        $client = static::createClient();
        $this->login($client, 'admin');

        $client->request('GET', $this->route);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('h1', 'Liste des utilisateurs');
    }

    public function testDeniedAccessWithUser()
    {
        $this->loadFixtures([AppFixtures::class]);
        $client = static::createClient();
        $this->login($client, 'user');

        $client->request('GET', $this->route);
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}