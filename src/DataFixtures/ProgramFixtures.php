<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $programNb = 0;
        for ($i = 0; $i < 6; $i++) {
            for ($j = 0; $j < 5; $j++) {
                $program = new Program();
                $program->setTitle($faker->realText(50));
                $program->setYear($faker->year());
                $program->setPoster('https://picsum.photos/300/200');
                $program->setCountry($faker->country());
                $program->setSynopsis($faker->realTextBetween());
                $program->setCategory($this->getReference('category_' . $i));
                $manager->persist($program);
                $this->addReference('program_' . $programNb, $program);
                $programNb++;
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
