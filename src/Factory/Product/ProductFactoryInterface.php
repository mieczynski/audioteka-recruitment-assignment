<?php

namespace App\Factory\Product;

use App\Entity\Product;

interface ProductFactoryInterface
{
    public function update(Product $product, string $name, int $price): Product;
}