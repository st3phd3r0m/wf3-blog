<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Posts;
use App\Entity\Categories;
use App\Form\CommentType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{

    /**
     * Route("/résultats-de-recherche/{id}", name="read_results", requirements={"id"="\d+"})
     * param string $search_str
     * param Request $request
     * return Response
     */
    // public function pageResultats(string $search_str, PaginatorInterface $paginator, Request $request)
    // {

    //     $posts = $paginator->paginate(
    //         //Selectionne toutes les données de la table "posts"
    //         //getRepository attend en paramètre, l'entité avec laquelle on souhaite travailler

    //         //$this->getDoctrine()->getRepository(Posts::class)->findBy(['categorie'=>$id], ['created_at' => 'DESC']),

    //         //Le numero de la page, si aucun numero, on force la page 1
    //         $request->query->getInt('page', 1),
    //         //Nombre d'élément par page
    //         6
    //     );

    //     return $this->render('blog/resultats.html.twig', [
    //         'posts'=>$posts
    //     ]);
    // }

    /**
     * 
     * @Route("/categorie/{slug}", name="read_categorie")
     * @param string $slug
     * @param Request $request
     * @return Response
     */
    public function pageCategorie(string $slug, PaginatorInterface $paginator, Request $request)
    {

        $categorie = $this->getDoctrine()->getRepository(Categories::class)->findOneBy(['slug' => $slug]);

        $posts = $paginator->paginate(
            $categorie->getPosts(),
            //Le numero de la page, si aucun numero, on force la page 1
            $request->query->getInt('page', 1),
            //Nombre d'élément par page
            2
        );

        return $this->render('blog/categorie.html.twig', [
            'categorie' => $categorie,
            'posts' => $posts
        ]);
    }


    /**
     * Route("/categorie2/{id}", name="read_categorie2", requirements={"id"="\d+"})
     * param int $id
     * param Request $request
     * return Response
     */
    // public function pageCategorie2(int $id, PaginatorInterface $paginator, Request $request)
    // {

    //     $categorie = $this->getDoctrine()->getRepository(Categories::class)->find($id);

    //     // Erreur 404 si aucun article trouvé
    //     if (!$categorie) {
    //         throw $this->createNotFoundException('La catégorie n\'existe pas.');
    //     }

    //     $posts = $paginator->paginate(
    //         //Selectionne toutes les données de la table "posts"
    //         //getRepository attend en paramètre, l'entité avec laquelle on souhaite travailler
    //         $this->getDoctrine()->getRepository(Posts::class)->findBy(['categorie' => $id], ['created_at' => 'DESC']),
    //         //Le numero de la page, si aucun numero, on force la page 1
    //         $request->query->getInt('page', 1),
    //         //Nombre d'élément par page
    //         2
    //     );

    //     return $this->render('blog/categorie2.html.twig', [
    //         'categorie' => $categorie,
    //         'posts' => $posts
    //     ]);
    // }


    /**
     * @Route("/post/{slug}", name="read_post")
     * @param string $slug
     * @param Request $request
     * @return Response
     */
    public function pageArticle(string $slug, Request $request)
    {

        //Selectionne 1 donnée de la table "posts" via son Id. getRepository attend en paramètre, l'entité avec laquelle on souhaite travailler
        $post = $this->getDoctrine()->getRepository(Posts::class)->findOneBy(['slug' => $slug]);

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
            $comment->setUser($this->getUser());

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

    /**
     * @Route("/results", name="results", methods={"GET"})
     * @return Response
     */
    public function search(Request $request, PaginatorInterface $paginator)
    {
        $expr = $request->query->get('search');
        $posts = $paginator->paginate(
            $this->getDoctrine()->getRepository(Posts::class)->search($expr),
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('limit', 2)/*limit per page*/
        );

        return $this->render('blog/index.html.twig',[
            'posts' => $posts,
            'expr' => $expr
        ]);
    }
}
