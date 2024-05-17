<?php

namespace App\Utils\Manager;

use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectRepository;

/**
 *
 */
abstract class AbstractBaseManager
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return ObjectRepository
     */
    abstract public function getRepository(): ObjectRepository;

    /**
     * @param object $entity
     */
    public function save(object $entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    /**
     * @param object $entity
     */
    public function remove(object $entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    /**
     * @param string $id
     * @return object|null
     */
    public function find(string $id): ?object
    {
        return $this->getRepository()->find($id);
    }
}