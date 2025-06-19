<?php

namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;

trait CommonRepositoryMethods
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {}

    public function save(object $entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function remove(object $entity): void
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }
}
