<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    const NB_OF_ACTORS = 10;
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create();
        for ($i = 0; $i < self::NB_OF_ACTORS; $i++) {
            $actor = new Actor();
            $actor->setFirstname($faker->firstName());
            $actor->setLastname($faker->lastName());
            $actor->setBirthDate($faker->dateTimeBetween('-80 years', '-20 years'));
            $actor->addProgram($this->getReference('program_' . rand(0, 29)));
            $actor->addProgram($this->getReference('program_' . rand(0, 29)));
            $actor->addProgram($this->getReference('program_' . rand(0, 29)));
            $manager->persist($actor);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProgramFixtures::class,
        ];
    }
}
