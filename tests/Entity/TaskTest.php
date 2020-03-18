<?php

namespace tests\Entity;

use App\Entity\Task;
use App\Tests\HelperEntityTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{
    use HelperEntityTrait;

    public function getEntity(): Task
    {
        return (new Task())
            ->setTitle('Title')
            ->setContent('Content')
        ;
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