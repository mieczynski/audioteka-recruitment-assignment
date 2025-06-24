<?php

namespace App\Service\Catalog;

interface ProductProvider
{
    /**
     * @return ProductInterface[]
     */
    public function getProducts(): iterable;

    public function exists(string $productId): bool;

    public function getTotalCount(): int;
}
