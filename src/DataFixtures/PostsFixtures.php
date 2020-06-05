<?php

namespace App\DataFixtures;

use App\Entity\Posts;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Mmo\Faker\PicsumProvider;
use Symfony\Component\HttpFoundation\File\File;

//implements OrderedFixtureInterface permet de charger une fixture comme PostsFixtures en priorité
class PostsFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        //Demarrage en langue francaise
        $faker = Faker\Factory::create('fr_FR');

        //Ajout d'une dependance à Faker afin d'utiliser les images
        $faker->addProvider(new PicsumProvider($faker));

        for ($i = 0; $i <= 50; $i++){

            //Récupération d'une image aléatoire
            $image = $faker->picsum('public/images/posts');

            $categorie = $this->getReference('categorie_'.rand(0, 10));

            //Création d'un post
            $post = new Posts;
            $post->setCategorie($categorie);
            $post->setTitle($faker->realText(80));
            $post->setContent($faker->paragraph(20));
            $post->setCreatedAt($faker->dateTimeBetween('-3 years', 'now'));
            $post->setUpdatedAt($faker->dateTimeBetween('-3 years', 'now'));
            //$post->setImageFile(new File($image));
            $post->setImage(str_replace('public/images/posts\\','',$image));

            $manager->persist($post);

            //Création d'une référence (une sorte de variable globale accessible uniquement par une fixture) afin de l'utiliser dans une autre fixture
            $this->addReference('post_'.$i, $post);
            
        }

        $manager->flush();
    }

    public function getOrder()
    {
        //PostsFixtures est le 3em à etre executé
        return 3;
    }
}
