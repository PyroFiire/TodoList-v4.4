<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AnonymeTaskFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //one task with author : null
        $task = new Task();
        $task->setTitle('Un titre rédigé par un utilisateur anonyme')
                ->setContent('Un contenue rédigé par utilisateur anonyme')
                ->setAuthor(null)
        ;
        $manager->persist($task);
        $manager->flush();
    }
}
