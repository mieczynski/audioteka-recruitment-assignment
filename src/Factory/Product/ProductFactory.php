<?php

namespace App\Factory\Product;

use App\Entity\Product;

class ProductFactory implements ProductFactoryInterface
{
    public function update(Product $product, string $name, int $price): Product
    {
        return $product
            ->setName($name)
            ->setPrice($price);
    }
}