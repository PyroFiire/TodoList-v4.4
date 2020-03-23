<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public const ADMIN_REFERENCE = 'admin';
    public const USER_REFERENCE = 'user';
    public const USER2_REFERENCE = 'user2';

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // ADMIN USER
        $admin = new User();
        $admin
            ->setUsername('admin')
            ->setEmail('admin@admin.com')
            ->setPassword($this->encoder->encodePassword($admin, 'admin'))
            ->setRoles(['ROLE_ADMIN'])
        ;
        $manager->persist($admin);
        $this->addReference(self::ADMIN_REFERENCE, $admin);

        // BASIC USER
        $user = new User();
        $user
            ->setUsername('user')
            ->setEmail('user@user.com')
            ->setPassword($this->encoder->encodePassword($user, 'goodPassword'))
            ->setRoles(['ROLE_USER'])
        ;
        $manager->persist($user);
        $this->addReference(self::USER_REFERENCE, $user);

        // BASIC USER2
        $user2 = new User();
        $user2
            ->setUsername('user2')
            ->setEmail('user2@user.com')
            ->setPassword($this->encoder->encodePassword($user2, 'goodPassword'))
            ->setRoles(['ROLE_USER'])
        ;
        $manager->persist($user2);
        $this->addReference(self::USER2_REFERENCE, $user2);

        $manager->flush();
    }
}
