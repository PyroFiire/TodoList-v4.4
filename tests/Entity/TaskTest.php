<?php

namespace tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{

    public function testClass()
    {
        $task = new Task();
        $this->assertInstanceOf(Task::class, $task);

        return $task;
    }

    /**
     * @depends testClass
     */
    public function testWithConstructor(Task $task)
    {
        $this->assertFalse($task->isDone());
        $this->assertInstanceOf(\DateTime::class, $task->getCreatedAt());
    }

    /**
     * @depends testClass
     */
    public function testGetAndSetMethods(Task $task)
    {
        $task->setCreatedAt(new \DateTime);
        $task->setTitle('TITLE');
        $task->setContent('CONTENT');
        $task->setAuthor(new User);
        $task->toggle(true);
        
        $this->assertNull($task->getId());
        $this->assertInstanceOf(\DateTime::class, $task->getCreatedAt());
        $this->assertEquals('TITLE', $task->getTitle());
        $this->assertEquals('CONTENT', $task->getContent());
        $this->assertInstanceOf(User::class, $task->getAuthor());
        $this->assertTrue($task->isDone());
    }

    // public function testWithOutConstructor()
    // {
    //     $taskMock = $this->getMockBuilder(Task::class)
    //       ->disableOriginalConstructor()
    //       ->getMock();

    //     $this->assertInstanceOf(Task::class, $taskMock);
    //     $this->assertNull($taskMock->isDone());
    //     $this->assertNull($taskMock->getCreatedAt());
    // }
}