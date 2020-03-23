<?php

namespace App\Tests\Controller;

use App\DataFixtures\UserFixtures;
use App\Repository\TaskRepository;
use App\Tests\HelperLoginTrait;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskCreateControllerTest extends WebTestCase
{
    use FixturesTrait;
    use HelperLoginTrait;

    /**
     * @var string
     */
    private $route;

    public function setUp()
    {
        $this->route = '/tasks/create';
        $this->loadFixtures([UserFixtures::class]);
    }

    public function testRedirectToLogin()
    {
        $client = static::createClient();
        $client->request('GET', $this->route);
        $this->assertResponseRedirects('/login');
    }

    public function testAccessWithUser()
    {
        $client = $this->login('user');
        $client->request('GET', $this->route);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('h1', 'Créer une tâche !');
    }

    public function testSuccessForm()
    {
        $client = $this->login('user');

        $crawler = $client->request('GET', $this->route);
        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'Un titre',
            'task[content]' => 'Du contenue...',
        ]);
        $client->submit($form);

        $task = self::$container->get(TaskRepository::class)->findOneByTitle('Un titre');
        $this->assertEquals('Un titre', $task->getTitle());
        $this->assertEquals('Du contenue...', $task->getContent());
        $this->assertFalse($task->isDone());
        $this->assertNotEmpty($task->getCreatedAt());
        $this->assertEquals('user', $task->getAuthor()->getUsername());

        $this->assertResponseRedirects('/tasks');
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testFailedForm()
    {
        $client = $this->login('user');

        $crawler = $client->request('GET', $this->route);
        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => '',
            'task[content]' => 'Du contenue...',
        ]);
        $client->submit($form);
        $this->assertSelectorExists('.form-error-message');
    }
}
