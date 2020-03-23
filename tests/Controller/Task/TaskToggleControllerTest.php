<?php

namespace App\Tests\Controller;

use App\DataFixtures\TaskFixtures;
use App\Repository\TaskRepository;
use App\Tests\HelperLoginTrait;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskToggleControllerTest extends WebTestCase
{
    use FixturesTrait;
    use HelperLoginTrait;

    /**
     * @var string
     */
    private $route;

    public function setUp()
    {
        $this->route = '/tasks/1/toggle';
        $this->loadFixtures([TaskFixtures::class]);
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

        $task = self::$container->get(TaskRepository::class)->findOneByTitle('Un titre rédigé par l\'user 1...');
        $this->assertTrue($task->isDone());

        $this->assertResponseRedirects('/tasks');
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('.alert.alert-success');
    }
}
