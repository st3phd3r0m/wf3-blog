<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Posts;
use App\Form\CommentType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{

    /**
     * @Route("/categorie/{id}", name="read_categorie", requirements={"id"="\d+"})
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function pageCategorie(int $id, PaginatorInterface $paginator, Request $request)
    {

        $posts = $paginator->paginate(
            //Selectionne toutes les données de la table "posts"
            //getRepository attend en paramètre, l'entité avec laquelle on souhaite travailler
            $this->getDoctrine()->getRepository(Posts::class)->findBy(['categorie'=>$id], ['created_at' => 'DESC']),
            //Le numero de la page, si aucun numero, on force la page 1
            $request->query->getInt('page', 1),
            //Nombre d'élément par page
            4
        );

        return $this->render('blog/categorie.html.twig', [
            'posts'=>$posts
        ]);
    }


    /**
     * @Route("/post/{id}", name="read_post", requirements={"id"="\d+"})
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function pageArticle(int $id, Request $request)
    {
        //Selectionne 1 donnée de la table "posts" via son Id. getRepository attend en paramètre, l'entité avec laquelle on souhaite travailler
        $post = $this->getDoctrine()->getRepository(Posts::class)->find($id);

        // Erreur 404 si aucun article trouvé
        if (!$post) {
            throw $this->createNotFoundException('Cet article est inéxistant.');
        }

        //Ajout du formulaire
        //Instanciation de l'entité Comments
        $comment = new Comments;
        //Création du formulaire avec pour parametres :
        // -Le formulaire genere en ligne de commande
        //-l'objet de l'intance ci-dessus
        $form = $this->createForm(CommentType::class, $comment);

        //Manipulation de la requete pour hydration automatique
        $form->handleRequest($request);

        //Si le formulaire est envoyé et celui-ci est valide !
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTime('now'));
            $comment->setPost($post);

            $doctrine = $this->getDoctrine()->getManager();
            $doctrine->persist($comment);
            $doctrine->flush();

            //Permet de vider les champs d'un formulaire
            $comment = new Comments;
            $form = $this->createForm(CommentType::class, $comment);

            //Envoi d'un message de succès
            $this->addFlash('success', 'Votre commentaire a bien été posté.');
        }

        return $this->render('blog/read.html.twig', [
            'post' => $post,
            'formComment' => $form->createView()
        ]);
    }


    /**
     * @Route("/", name="blog")
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request)
    {

        //Selectionne toutes les données de la table "posts"
        //getRepository attend en paramètre, l'entité avec laquelle on souhaite travailler
        // $posts = $this->getDoctrine()->getRepository(Posts::class)->findAll(),

        $posts = $paginator->paginate(
            //Selectionne toutes les données de la table "posts"
            //getRepository attend en paramètre, l'entité avec laquelle on souhaite travailler
            $this->getDoctrine()->getRepository(Posts::class)->findBy([], ['created_at' => 'DESC']),
            //Le numero de la page, si aucun numero, on force la page 1
            $request->query->getInt('page', 1),
            //Nombre d'élément par page
            10
        );

        //Dump Data
        //dd($posts);

        return $this->render('blog/index.html.twig', [
            'posts' => $posts
        ]);
    }
}
