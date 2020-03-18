<?php

namespace App\Tests\Controller;

use App\Tests\HelperLoginTrait;
use App\DataFixtures\AppFixtures;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserCreateControllerTest extends WebTestCase
{
    use FixturesTrait;
    use HelperLoginTrait;

    /**
     * @var string
     */
    private $route;

    public function setUp()
    {
        $this->route = '/users/create';
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
        $this->assertSelectorTextContains('h1', 'Créer un utilisateur !');
    }

    public function testDeniedAccessWithUser()
    {
        $this->loadFixtures([AppFixtures::class]);
        $client = static::createClient();
        $this->login($client, 'user');

        $client->request('GET', $this->route);
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testSuccessForm()
    {
        $this->loadFixtures([AppFixtures::class]);
        $client = static::createClient();
        $this->login($client, 'admin');

        $crawler = $client->request('GET', $this->route);
        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'Username',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'email@email.com',
            'user[roles]' => ['ROLE_USER'],
        ]);
        $client->submit($form);

        $user = self::$container->get(UserRepository::class)->findOneByUsername('Username');
        $this->assertEquals('Username', $user->getUsername());

        $this->assertResponseRedirects('/');
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testFailedForm()
    {
        $this->loadFixtures([AppFixtures::class]);
        $client = static::createClient();
        $this->login($client, 'admin');

        $crawler = $client->request('GET', $this->route);
        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'aze',
            'user[password][first]' => 'password',
            'user[password][second]' => 'fzefz',
            'user[email]' => 'email@email.com',
            'user[roles]' => ['ROLE_USER'],
        ]);
        $client->submit($form);
        $this->assertSelectorExists('.form-error-message');
    }
}