<?php

namespace App\Tests\Controller;

use App\Tests\HelperLoginTrait;
use App\DataFixtures\AppFixtures;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LogoutControllerTest extends WebTestCase
{
    use FixturesTrait;
    use HelperLoginTrait;

    public function testPage()
    {
        $this->loadFixtures([AppFixtures::class]);
        $client = static::createClient();
        $this->login($client, 'user');

        $client->request('GET', '/logout');
        $this->assertResponseRedirects();
    }
}
