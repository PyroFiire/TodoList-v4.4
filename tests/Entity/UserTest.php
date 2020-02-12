<?php

namespace tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Collections\ArrayCollection;

class UserTest extends TestCase
{

    public function testClass()
    {
        $user = new User();
        $this->assertInstanceOf(User::class, $user);

        return $user;
    }

    /**
     * @depends testClass
     */
    public function testWithConstructor(User $user)
    {
        $this->assertInstanceOf(ArrayCollection::class, $user->getTasks());
    }

    /**
     * @depends testClass
     */
    public function testGetAndSetMethods(User $user)
    {
        $user->setUsername('username');
        $user->setEmail('email@email.com');
        $user->setPassword('password');
        $user->setRoles(['ROLE_USER']);

        
        $this->assertNull($user->getId());
        $this->assertEquals('username', $user->getUsername());
        $this->assertEquals('email@email.com', $user->getEmail());
        $this->assertEquals('password', $user->getPassword());
        $this->assertNull($user->getSalt());
        $this->assertEmpty($user->eraseCredentials());
        $this->assertEquals(['ROLE_USER'], $user->getRoles());

    }
}