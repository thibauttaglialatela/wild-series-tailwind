<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    const SEASON_NUMBER = 150;
    private SluggerInterface $slugger;

    /**
     * @param SluggerInterface $slugger
     */
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $episodeNb = 1;
        for ($i = 0; $i < self::SEASON_NUMBER; $i++) {
            for ($j = 0; $j < 10; $j++) {
                $episode = new Episode();
                $episode->setNumber($j + 1);
                $episode->setTitle($faker->realText(50));
                $slug = $this->slugger->slug($episode->getTitle());
                $episode->setSlug($slug);
                $episode->setSynopsis($faker->sentence(15));
                $episode->setDuration($faker->numberBetween(0, 90));
                $episode->setSeason($this->getReference('season_' . $i));
                $manager->persist($episode);
                $this->addReference('episode_' . $episodeNb, $episode);
                $episodeNb++;
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
