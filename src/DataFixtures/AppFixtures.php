<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // ADMIN USER
        $user = new User();
        $user
            ->setUsername('admin')
            ->setEmail('admin@admin.com')
            ->setPassword($this->encoder->encodePassword($user, 'admin'))
            ->setRoles(['ROLE_ADMIN'])
        ;
        $users[] = $user;
        $manager->persist($user);

        // 9 USERS
        for ($i=0; $i < 9 ; $i++) { 
            $user = new User();
            $user
                ->setUsername('username'.$i)
                ->setEmail('email'.$i.'@email.com')
                ->setPassword($this->encoder->encodePassword($user, 'password'))
                ->setRoles(['ROLE_USER'])
            ;
            $users[] = $user;
            $manager->persist($user);
        }
        $manager->flush();

        // 30 TASKS
        for ($i=0; $i < 30 ; $i++) { 
            $task = new Task();
            $task->setTitle('Title '.$i);
            $task->setContent('content content and content '.$i);
            $task->setAuthor($users[random_int(0,9)]);
            ;
            $manager->persist($task);
        }


        $manager->flush();
    }
}
