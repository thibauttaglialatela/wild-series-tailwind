<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    const CATEGORIES = [
        'categorie_Action',
        'categorie_Aventure',
        'categorie_Animation',
        'categorie_Fantastique',
        'categorie_Horreur',
    ];
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        foreach(self::CATEGORIES as $categorieName) {
            for ($i = 0; $i<5; $i++){
                $program = new Program();
                $program->setTitle($faker->realText(50));
                $program->setCountry($faker->country());
                $program->setYear($faker->year());
                $program->setSynopsis($faker->sentence(50));
                $program->setPoster('https://picsum.photos/300/200');
                $program->setCategory($this->getReference($categorieName));
                $manager->persist($program);
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
