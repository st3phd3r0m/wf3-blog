<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CategoriesFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        //Demarrage en langue francaise
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i <= 10; $i++) {

            $categorie = new Categories;
            $categorie->setName($faker->word);

            $manager->persist($categorie);

            //Création d'une référence (une sorte de variable globale accessible uniquement par une fixture) afin de l'utiliser dans une autre fixture
            $this->addReference('categorie_'.$i, $categorie);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        //PostsFixtures est le 1er à etre executé
        return 1;
    }
}
