<?php

namespace App\Repository;

trait CommonRepositoryMethods
{
    public function save(object $entity): void
    {
        $this->_em->persist($entity);
        $this->_em->flush();
    }

    public function remove(object $entity): void
    {
        $this->_em->remove($entity);
        $this->_em->flush();
    }

    public function flush(): void
    {
        $this->_em->flush();
    }
}
