<?php

namespace tests\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function assertHasErrors($object, int $number = 0): void
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($object);
        $messages = [];
        /** @var ConstraintViolation $error */
        foreach($errors as $error) {
            $messages[] = strtoupper($error->getPropertyPath()) . ' => ' . $error->getMessage();
        }

        $this->assertCount($number, $errors, implode(' ||| ' , $messages));
    }

    public function getEntity(): User
    {
        return (new User())
            ->setUsername('username')
            ->setEmail('email@email.com')
            ->setPassword('password')
            ->setRoles(['ROLE_USER'])
        ;
    }

    public function getText(int $number): string
    {
        $text = '';
        for ($i=0; $i < $number; $i++) {
            $text = $text.'a';
        }

        return $text;
    }

    public function testValidEntity()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testInvalidBlanckCodeEntity()
    {
        $this->assertHasErrors($this->getEntity()->setUsername(''), 1);
        $this->assertHasErrors($this->getEntity()->setEmail(''), 1);
        $this->assertHasErrors($this->getEntity()->setPassword(''), 1);
        $this->assertHasErrors($this->getEntity()->setRoles([]), 1);
    }

    public function testInvalidLengthEntity()
    {
        $this->assertHasErrors($this->getEntity()->setUsername($this->getText(256)), 1);
        $this->assertHasErrors($this->getEntity()->setEmail($this->getText(256).'@test.com'), 1);
        $this->assertHasErrors($this->getEntity()->setPassword($this->getText(256)), 1);
    }

    public function testInvalidEmailEntity()
    {
        $this->assertHasErrors($this->getEntity()->setEmail('test\ger@test.com'), 1);
        $this->assertHasErrors($this->getEntity()->setEmail('test-test.com'), 1);
        $this->assertHasErrors($this->getEntity()->setEmail('test@@test.com'), 1);
    }
}