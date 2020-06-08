<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Form\PostType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    /**
     * @Route("/admin/edit/post/{id}", name="edit_Post")
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function editPost(int $id, Request $request)
    {

        //Selectionne 1 donnée de la table "posts" via son Id. getRepository attend en paramètre, l'entité avec laquelle on souhaite travailler
        $post = $this->getDoctrine()->getRepository(Posts::class)->find($id);

        // Erreur 404 si aucun article trouvé
        if (!$post) {
            throw $this->createNotFoundException('Cet article est inéxistant.');
        }

        //Ajout du formulaire
        //Création du formulaire avec pour parametres :
        // -Le formulaire genere en ligne de commande
        // -l'objet $post sélectionné ci-dessus ci-dessus        
        $form = $this->createForm(PostType::class, $post);
        //Manipulation de la requete pour hydration automatique
        $form->handleRequest($request);

        //Si le formulaire est envoyé et celui-ci est valide !
        if ($form->isSubmitted() && $form->isValid()) {

            $post->setUpdatedAt(new \DateTime('now'));
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            //Envoi d'un message de succès
            $this->addFlash('success', 'L\'article a bien été mis à jour.');
        }

        return $this->render('admin/edit.html.twig', [
            'editForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/delete/post/{id}", name="delete_Post")
     * @param int $id
     */
    public function deletePost(int $id)
    {

        $post = $this->getDoctrine()->getRepository(Posts::class)->find($id);

        // Erreur 404 si aucun article trouvé
        if (!$post) {
            throw $this->createNotFoundException('Cet article n\'existe pas.');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($post);
        $entityManager->flush();

        $this->addFlash('success', "L'article " . $post->getTitle() . " a bien été supprimé.");

        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/admin", name="admin")
     * @param Request $request
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request)
    {

        $posts = $paginator->paginate(
            //Selectionne toutes les données de la table "posts"
            //getRepository attend en paramètre, l'entité avec laquelle on souhaite travailler
            $this->getDoctrine()->getRepository(Posts::class)->findBy([], ['created_at' => 'DESC']),
            //Le numero de la page, si aucun numero, on force la page 1
            $request->query->getInt('page', 1),
            //Nombre d'élément par page
            10
        );

        return $this->render('admin/index.html.twig', [
            'posts' => $posts
        ]);
    }
}
