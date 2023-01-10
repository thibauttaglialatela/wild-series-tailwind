<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    const EPISODE_NUMBER = 1000;

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create(locale: 'fr_FR');
        for ($i = 1; $i <= self::EPISODE_NUMBER; $i++) {
            for ($j = 0; $j < 3; $j++) {
                $comment = new Comment();
                $comment->setComment($faker->realTextBetween());
                $comment->setRate($faker->numberBetween(0, 10));
                $comment->setAuthor($this->getReference('contributor@mail.com'));
                $comment->setEpisode($this->getReference('episode_' . $i));
                $comment->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween()));
                $manager->persist($comment);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            EpisodeFixtures::class,
            UserFixtures::class
        ];
    }
}
