<?php

namespace App\Service\Catalog;

interface ProductServiceInterface
{
    public function add(string $name, int $price): ProductInterface;
    public function update(string $id, string $name, int $price): ProductInterface;
    public function remove(string $id): void;
}