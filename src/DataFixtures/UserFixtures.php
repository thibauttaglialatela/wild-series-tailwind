<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        return $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $contributor = new User();
        $plainTextPassword = 'wildPassord';
        $contributor->setEmail('contributor@mail.com');
        $contributor->setFirstname($faker->firstName());
        $contributor->setLastname($faker->lastName());
        $contributor->setRoles(['ROLE_CONTRIBUTOR']);
        $contributor->setPassword($this->passwordHasher->hashPassword($contributor, $plainTextPassword));
        $manager->persist($contributor);
        $this->addReference('contributor@mail.com', $contributor);

        $admin = new User();
        $adminPlainTextPassword = 'adminPassword';
        $admin->setEmail('admin@mail.com');
        $admin->setFirstname('John');
        $admin->setLastname('Connor');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, $adminPlainTextPassword));
        $manager->persist($admin);

        $manager->flush();
        $this->addReference('contributor', $contributor);
    }
}
