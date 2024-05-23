<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\EditCategoryFormType;
use App\Form\Handler\ProductFormHandler;
use App\Repository\CategoryRepository;
use App\Utils\Manager\ProductManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/category", name="admin_category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function list(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findBy([], ['id' => 'DESC']);
        return $this->render('admin/category/list.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @Route("/add", name="add")
     */
    public function edit(Request $request, Category $category = null): Response
    {
        if (!$category) {
            $category = new Category();
        }

        $form = $this->createForm(EditCategoryFormType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        }

        return $this->render('admin/category/edit.html.twig', [
            'category' => $category,
            'form'     => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Category $category): Response
    {
        return null;
    }
}