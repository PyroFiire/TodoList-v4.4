<?php

namespace App\Tests\Controller;

use App\DataFixtures\UserFixtures;
use App\Tests\HelperLoginTrait;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LogoutControllerTest extends WebTestCase
{
    use FixturesTrait;
    use HelperLoginTrait;

    public function testPage()
    {
        $this->loadFixtures([UserFixtures::class]);
        $client = $this->login('user');
        $client->request('GET', '/logout');
        $this->assertResponseRedirects();
    }
}
