<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersFixtures extends Fixture implements OrderedFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i <= 10; $i++) {

            $user = new Users;
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setEmail($faker->email);
            $user->setPassword($this->encoder->encodePassword($user, 'secret'));
            //$user->setPassword('secret');
            $user->setIsVerified(1);

            $manager->persist($user);

            //Création d'une référence (une sorte de variable globale accessible uniquement par une fixture) afin de l'utiliser dans une autre fixture
            $this->addReference('user_' . $i, $user);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        //UsersFixtures est le 2em à etre executé
        return 2;
    }
}
