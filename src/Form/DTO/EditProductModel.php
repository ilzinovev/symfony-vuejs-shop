<?php

namespace App\Form\DTO;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class EditProductModel
{
    /**
     * @var int
     */
    public $id;
    /**
     * @Assert\NotBlank(message="Please enter a title")
     * @var string
     */
    public $title;

    /**
     * @Assert\NotBlank(message="Please enter a price")
     * @Assert\GreaterThanOrEqual(value="0")
     * @var string
     */
    public $price;

    /**
     * @Assert\File(
     *     maxSize= "2M",
     *     mimeTypes= {"image/jpeg"},
     *     mimeTypesMessage="Please upload a valid image"
     * )
     * @var UploadedFile|null
     */
    public $newImage;

    /**
     * @Assert\NotBlank(message="Please enter a quantity")
     * @var int
     */
    public $quantity;

    /**
     * @var string
     */
    public $description;

    /**
     * @var bool
     */
    public $isPublished;
    /**
     * @var bool
     */
    public $isDeleted;

    /**
     * @Assert\NotBlank(message="Please select a category")
     * @var Category
     */
    public $category;

    public static function makeFromProduct(?Product $product): self
    {
        $model = new self();
        if (!$product) {
            return $model;
        }
        $model->id          = $product->getId();
        $model->title       = $product->getTitle();
        $model->price       = $product->getPrice();
        $model->quantity    = $product->getQuantity();
        $model->description = $product->getDescription();
        $model->isPublished = $product->getIsPublished();
        $model->isDeleted   = $product->getIsDeleted();
        return $model;
    }


}