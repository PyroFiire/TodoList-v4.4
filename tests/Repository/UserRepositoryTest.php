<?php

namespace tests\Repository;

use App\DataFixtures\AppFixtures;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserRepositoryTest extends WebTestCase
{
    use FixturesTrait;
    
    public function setUp(){
        self::bootKernel();
        // On charge les fixtures dans une classe Spécifique
        $this->loadFixtures([AppFixtures::class]);
        // Ou depuis un fichier PHP renvoyant un tableau
        //$this->loadFixtureFiles([__DIR__ . '/users.php']);
    }

    public function testCount() {
        $nbUsers = self::$container->get(UserRepository::class)->count([]);
        $this->assertEquals(10, $nbUsers);
    }

    public function testAdminUser(){
        $admin = self::$container->get(UserRepository::class)->findOneByUsername('admin');
        $this->assertEquals('admin', $admin->getUsername());
        $this->assertEquals('admin@admin.com', $admin->getEmail());
        $this->assertEquals(['ROLE_ADMIN'], $admin->getRoles());
    }
}