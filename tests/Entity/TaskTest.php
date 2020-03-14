<?php

namespace tests\Entity;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
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

    public function getEntity(): Task
    {
        return (new Task())
            ->setTitle('Title')
            ->setContent('Content')
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
        $this->assertHasErrors($this->getEntity()->setTitle(''), 1);
        $this->assertHasErrors($this->getEntity()->setContent(''), 1);
    }

    public function testInvalidLengthEntity()
    {
        $this->assertHasErrors($this->getEntity()->setTitle($this->getText(256)), 1);
        $this->assertHasErrors($this->getEntity()->setContent($this->getText(2001)), 1);
    }
}