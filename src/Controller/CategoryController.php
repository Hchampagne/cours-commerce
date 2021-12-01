<?php

namespace App\Controller;

use App\Form\CategoryType;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryController extends AbstractController
{



    /**
     * @Route ("/admin/category/create", name ="category_create")
     */
    public function create(Request $request, SluggerInterface $slugger, EntityManagerInterface $em)
    {


        $form = $this->createForm(CategoryType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $category = $form->getData();
            $category->setSlug(strtolower($slugger->slug($category->getName())));

            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        $formview = $form->createView();

        return $this->render('category/create.html.twig', [
            'formview' => $formview
        ]);
    }


    /**
     * @Route("/admin/category/{id}/edit", name="category_edit")
     *
     */
    public function edit($id, CategoryRepository $categoryRepository, EntityManagerInterface $em, Request $request)
    {
        $category = $categoryRepository->find($id);
        $form = $this->createForm(CategoryType::class, $category);


        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        $formview = $form->createView();

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'formview' => $formview
        ]);
    }
}
