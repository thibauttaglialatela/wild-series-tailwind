<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $seasonNb = 0;
        for ($i = 0; $i < 30; $i++) {
            for ($j = 0; $j < 5; $j++) {
                $season = new Season();
                $season->setNumber($j + 1);
                $season->setYear(intval($faker->year()));
                $season->setDescription($faker->realTextBetween());
                $season->setProgram($this->getReference('program_' . $i));
                $manager->persist($season);
                $this->addReference('season_' . $seasonNb, $season);
                $seasonNb++;
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProgramFixtures::class,
        ];
    }
}
