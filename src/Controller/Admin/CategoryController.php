<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\Admin\EditCategoryFormType;
use App\Form\DTO\EditCategoryModel;
use App\Form\Handler\OrderFormHandler;
use App\Repository\CategoryRepository;
use App\Utils\Manager\OrderManager;
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
        $categories = $categoryRepository->findBy(['isDeleted' => false], ['id' => 'DESC']);
        return $this->render('admin/category/list.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @Route("/add", name="add")
     */
    public function edit(
        Request $request,
        OrderFormHandler $categoryFormHandler,
        Category $category = null
    ): Response {
        $editCategoryModel = EditCategoryModel::makeFromCategory($category);

        $form = $this->createForm(EditCategoryFormType::class, $editCategoryModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $categoryFormHandler->processEditForm($editCategoryModel);
            $this->addFlash('success', 'updated');

            return $this->redirectToRoute('admin_category_edit', ['id' => $category->getId()]);
        }
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', 'not updated');
        }

        return $this->render('admin/category/edit.html.twig', [
            'category' => $category,
            'form'     => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Category $category, OrderManager $categoryManager): Response
    {
        $categoryManager->remove($category);
        $this->addFlash('success', 'deleted');


        return $this->redirectToRoute('admin_category_list');
    }
}