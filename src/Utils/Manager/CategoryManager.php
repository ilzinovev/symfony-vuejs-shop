<?php
namespace App\Utils\Manager;

use App\Entity\Category;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class CategoryManager extends AbstractBaseManager
{

    public function __construct(EntityManagerInterface $entityManager)
    {
                parent::__construct($entityManager);

    }
    /**
     * @return ObjectRepository
     */
    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Category::class);
    }

    /**
     * @param Category $category
     */
    public function remove(object $category)
    {
        $category->setIsDeleted(true);
        /**
         * @var Product $product
         */
        foreach ($category->getProducts()->getValues() as $product) {
            $category->setIsDeleted(true);
        }
        $this->save($category);

    }
}