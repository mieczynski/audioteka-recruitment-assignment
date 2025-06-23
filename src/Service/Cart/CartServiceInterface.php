<?php

namespace App\Service\Cart;

interface CartServiceInterface
{
    public function addProduct(string $cartId, string $productId): void;
    public function removeProduct(string $cartId, string $productId): void;
    public function updateProductQuantity(string $cartId, string $productId, int $quantity): void;
    public function createCart(): CartInterface;
}