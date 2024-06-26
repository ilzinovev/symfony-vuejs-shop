<?php

namespace App\Form\Handler;


use App\Entity\Product;
use App\Form\DTO\EditProductModel;
use App\Utils\File\FileSaver;
use App\Utils\Manager\ProductManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;

class ProductFormHandler
{

    /**
     * @var FileSaver
     */
    private $fileSaver;
    /**
     * @var ProductManager
     */
    private $productManager;

    public function __construct(ProductManager $productManager, FileSaver $fileSaver)
    {

        $this->fileSaver     = $fileSaver;
        $this->productManager = $productManager;
    }

    /**
     * @param EditProductModel $editProductModel
     * @param Form $form
     * @return Product|object|null
     */
    public function processEditForm(EditProductModel $editProductModel, Form $form)
    {
        $product = new Product();

        if($editProductModel->id){
            $product = $this->productManager->find($editProductModel->id);

        }
        $product->setTitle($editProductModel->title);
        $product->setPrice($editProductModel->price);
        $product->setDescription($editProductModel->description);
        $product->setQuantity($editProductModel->quantity);
        $product->setIsPublished($editProductModel->isPublished);
        $product->setIsDeleted($editProductModel->isDeleted);
        $product->setCategory($editProductModel->category);

        $this->productManager->save($product);


        $newImageFile = $form->get('newImage')->getData();


        $tempImageFileName = $newImageFile ?
            $this->fileSaver->saveUploadedFileIntoTemp($newImageFile) : null;



        $this->productManager->updateProductImages($product,$tempImageFileName);

        $this->productManager->save($product);

        return $product;
    }

}