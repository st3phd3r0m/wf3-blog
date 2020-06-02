<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/test", name="home")
     */
    public function test()
    {

        $tableau = [
            'animaux' => 'chats',
            'voiture' => 'Renault Mégane',
            'maison' => 'Villa 375m²'
        ];

        $tableau2 = [];

        return $this->render('home/test.html.twig', [
            'prenom' => 'Guillaume',
            'age' => 37,
            'possessions' => $tableau,
            'possessions2' => $tableau2
        ]);
    }

    /**
     * @Route("/autre/page/{nom}/{prenom}", name="other_page")
     */
    public function otherPage($nom, $prenom)
    {
        $welcome = 'Welcome ' . $prenom . ' ' . $nom;

        return $this->render('home/otherPage.html.twig', [
            'welcome' => $welcome
        ]);
    }
}
