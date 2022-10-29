<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
$faker = Factory::create();
        for ($i = 0; $i < 5; $i++){
            for ($j = 0; $j < 10; $j++){
                $episode = new Episode();
                $episode->setNumber($j + 1);
                $episode->setTitle($faker->realText(50));
                $episode->setSynopsis($faker->realTextBetween());
                $episode->setSeason($this->getReference('season_' . $i));
                $manager->persist($episode);
            }
        }


        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SeasonFixtures::class,
        ];
    }
}
