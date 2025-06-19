<?php

namespace App\Service\Catalog;

interface ProductServiceInterface
{
    public function add(string $name, int $price): Product;

    public function remove(string $id): void;
}