<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\EditProductFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="main_homepage")
     */
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $productList   = $entityManager->getRepository(Product::class)->findAll();


        return $this->render('main/default/index.html.twig', [

        ]);
    }


    /**
     * @Route("/edit-product/{id}", methods="GET|POST", name="product_edit" , requirements={"id"="\d+"})
     * @Route("/add-product", methods="GET|POST", name="product_add")
     */
    public function editProduct(Request $request, int $id = null): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        if ($id) {
            $product = $entityManager->getRepository(Product::class)->find($id);
        } else {
            $product = new Product();
        }

        $form = $this->createForm(EditProductFormType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('product_edit', ['id' => $product->getId()]);
        }

        return $this->render('main/default/edit_product.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
