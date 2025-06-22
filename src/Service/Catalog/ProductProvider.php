<?php

namespace App\Service\Catalog;

interface ProductProvider
{
    /**
     * @return ProductInterface[]
     */
    public function getProducts(int $page = 0, int $count = 3): iterable;

    public function exists(string $productId): bool;

    public function getTotalCount(): int;
}
