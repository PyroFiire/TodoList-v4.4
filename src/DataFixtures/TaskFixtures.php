<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [UserFixtures::class];
    }

    public function load(ObjectManager $manager): void
    {
        //one task with author : user
        $task = new Task();
        $task->setTitle('Un titre rédigé par l\'user 1...')
             ->setContent('Un contenue rédigé par l\'user 1...')
             ->setAuthor($this->getReference(UserFixtures::USER_REFERENCE))
        ;
        $manager->persist($task);
        $manager->flush();
    }
}
