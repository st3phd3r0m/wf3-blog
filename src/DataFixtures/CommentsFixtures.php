<?php

namespace App\DataFixtures;

use App\Entity\Comments;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

//implements OrderedFixtureInterface permet de charger une fixture comme PostsFixtures en priorité
class CommentsFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Faker\Factory::create('fr_FR');

        for($i = 0; $i <= 500; $i++){

            //Récupère une référence (créée dans PostsFixtures) selon une clé
            $post = $this->getReference('post_'.rand(0, 50));

            $comment = new Comments;
            $comment->setComment($faker->realText());
            $comment->setPost($post);
            $comment->setCreatedAt($faker->dateTimeBetween('-3 years', 'now'));
            
            $manager->persist($comment);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        //CommentsFixtures est le 2em à etre executé
        return 3;
    }
}
