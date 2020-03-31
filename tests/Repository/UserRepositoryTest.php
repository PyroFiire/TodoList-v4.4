<?php

namespace tests\Repository;

use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserRepositoryTest extends WebTestCase
{
    use FixturesTrait;

    public function setUp()
    {
        self::bootKernel();
        $this->loadFixtures([UserFixtures::class]);
    }

    public function testCount(): void
    {
        $nbUsers = self::$container->get(UserRepository::class)->count([]);
        $this->assertEquals(3, $nbUsers);
    }

    public function testAdminUser(): void
    {
        $admin = self::$container->get(UserRepository::class)->findOneByUsername('admin');
        $this->assertEquals('admin', $admin->getUsername());
        $this->assertEquals('admin@admin.com', $admin->getEmail());
        $this->assertEquals(['ROLE_ADMIN'], $admin->getRoles());
    }
}
