<?php

namespace App\Tests\Controller;

use App\Tests\HelperLoginTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskListControllerTest extends WebTestCase
{
    use HelperLoginTrait;

    /**
     * @var string
     */
    private $route;

    public function setUp()
    {
        $this->route = '/tasks';
    }

    public function testRedirectToLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', $this->route);
        $this->assertResponseRedirects('/login');
    }

    public function testAccessWithAdmin(): void
    {
        $client = $this->login('admin');
        $client->request('GET', $this->route);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('h1', 'Liste des tâches !');
    }

    public function testAccessWithUser(): void
    {
        $client = $this->login('user');
        $client->request('GET', $this->route);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
