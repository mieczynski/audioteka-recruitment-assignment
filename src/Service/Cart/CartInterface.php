<?php

namespace App\Service\Cart;

use App\Entity\CartProducts;
use App\Service\Catalog\ProductInterface;

interface CartInterface
{
    public function getId(): string;
    public function getTotalPrice(): int;
    public function isFull(): bool;
    /**
     * @return ProductInterface[]
     */
    public function getProducts(): iterable;
    public function addProduct(ProductInterface $product): void;
    public function removeProduct(ProductInterface $product): void;
    public function updateProductQuantity(ProductInterface $product, int $quantity): void;
    public function findCartProduct(ProductInterface $product): ?CartProducts;
}
