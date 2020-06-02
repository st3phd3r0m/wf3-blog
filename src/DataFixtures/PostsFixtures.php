<?php

namespace App\DataFixtures;

use App\Entity\Posts;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class PostsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i <= 50; $i++){
            $post = new Posts;
            $post->setTitle($faker->realText(80));
            $post->setContent($faker->paragraph(20));
            $post->setCreatedAt($faker->dateTimeBetween('-3 years', 'now'));
            
            $manager->persist($post);
        }

        $manager->flush();
    }
}
