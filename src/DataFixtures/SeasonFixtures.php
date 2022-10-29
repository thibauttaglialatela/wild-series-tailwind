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
        for ($i = 0; $i < 5; $i++){
            for($j = 0; $j < 5; $j++){
                $season = new Season();
                $season->setNumber($j + 1);
                $season->setYear($faker->date('Y'));
                $season->setDescription($faker->realTextBetween());
                $season->setProgram($this->getReference('Program_' . $i));
                $manager->persist($season);
                $this->setReference('season_' . $j, $season);
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
