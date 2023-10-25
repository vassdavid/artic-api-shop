<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

class AppFixtures extends Fixture
{
    const USERS_DATA = [
        'user1@email.com' => 'password',
        'user2@email.com' => 'password',
    ];

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) { }

    public function load(ObjectManager $manager): void
    {
        foreach (self::USERS_DATA as $email => $password) {
            $user = new User();
            $user->setEmail($email);
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $password
            );
            $user->setPassword($hashedPassword);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
