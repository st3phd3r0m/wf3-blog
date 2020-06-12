<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/categories", name="categories_")
 */
class CategoriesController extends AbstractController
{
    /**
     * @IsGranted("ROLE_EDITOR")
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(CategoriesRepository $categoriesRepository, PaginatorInterface $paginator, Request $request): Response
    {

        $this->denyAccessUnlessGranted('ROLE_EDITOR');

        $categories = $paginator->paginate(
            //Selectionne toutes les données de la table "posts"
            //getRepository attend en paramètre, l'entité avec laquelle on souhaite travailler
            $categoriesRepository->findAll(),
            //Le numero de la page, si aucun numero, on force la page 1
            $request->query->getInt('page', 1),
            //Nombre d'élément par page
            10
        );

        return $this->render('categories/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @IsGranted("ROLE_EDITOR")
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_EDITOR');

        $category = new Categories();
        $form = $this->createForm(CategoriesType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            //Envoi d'un message de succès
            $this->addFlash('success', 'La catégorie a bien été créée.');

            return $this->redirectToRoute('categories_index');
        }


        return $this->render('categories/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Categories $category): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(CategoriesType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            //Envoi d'un message de succès
            $this->addFlash('success', 'La catégorie a bien été modifiée.');

            return $this->redirectToRoute('categories_index');
        }

        return $this->render('categories/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Categories $category): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();

            //Envoi d'un message de succès
            $this->addFlash('success', 'La catégorie a bien été supprimée.');
        }

        return $this->redirectToRoute('categories_index');
    }
}
