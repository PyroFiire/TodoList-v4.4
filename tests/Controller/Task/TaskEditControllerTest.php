<?php

namespace App\Tests\Controller;

use App\DataFixtures\TaskFixtures;
use App\Repository\TaskRepository;
use App\Tests\HelperLoginTrait;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskEditControllerTest extends WebTestCase
{
    use FixturesTrait;
    use HelperLoginTrait;

    /**
     * @var string
     */
    private $route;

    public function setUp()
    {
        $this->route = '/tasks/1/edit';
        $this->loadFixtures([TaskFixtures::class]);
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
        $this->assertSelectorTextContains('h1', 'Modifier une tâche !');
    }

    public function testAccessWithGoodAuthor(): void
    {
        $client = $this->login('user');

        $client->request('GET', $this->route);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('h1', 'Modifier une tâche !');
    }

    public function testDeniedAccessWithBadAuthor(): void
    {
        $client = $this->login('user2');
        $client->request('GET', $this->route);
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testSuccessForm(): void
    {
        $client = $this->login('user');

        $crawler = $client->request('GET', $this->route);
        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => 'Un titre édité',
            'task[content]' => 'Du contenue édité...',
        ]);
        $client->submit($form);

        $task = self::$container->get(TaskRepository::class)->findOneByTitle('Un titre édité');
        $this->assertEquals('Un titre édité', $task->getTitle());
        $this->assertEquals('Du contenue édité...', $task->getContent());
        $this->assertEquals('user', $task->getAuthor()->getUsername());

        $this->assertResponseRedirects('/tasks');
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testFailedForm(): void
    {
        $client = $this->login('user');

        $crawler = $client->request('GET', $this->route);
        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => 'a',
            'task[content]' => 'Du contenue...',
        ]);
        $client->submit($form);
        $this->assertSelectorExists('.form-error-message');
    }
}
