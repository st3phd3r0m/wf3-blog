<?php

namespace App\Controller;

use App\Entity\Posts;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="blog")
     */
    public function index()
    {
        //Selectionne toutes les données de la table "posts"
        //getRepository attend en paramètre, l'entité avec laquelle on souhaite travailler
        $posts = $this->getDoctrine()->getRepository(Posts::class)->findAll();

        //Dump Data
        //dd($posts);

        return $this->render('blog/index.html.twig', [
            'posts'=> $posts
        ]);
    }
}
