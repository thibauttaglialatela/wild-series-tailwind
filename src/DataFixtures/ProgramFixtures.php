<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
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
        $programNb = 0;
        for ($i = 0; $i < 6; $i++) {
            for ($j = 0; $j < 5; $j++) {
                $program = new Program();
                $program->setTitle($faker->realText(50));
                $slug = $this->slugger->slug($program->getTitle());
                $program->setSlug($slug);
                $program->setYear($faker->year());
                $program->setCountry($faker->country());
                $program->setSynopsis($faker->realTextBetween());
                $program->setOwner($this->getReference('contributor'));
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
            UserFixtures::class,
        ];
    }
}
