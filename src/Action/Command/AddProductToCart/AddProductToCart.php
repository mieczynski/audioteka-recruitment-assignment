<?php

namespace App\Action\Command\AddProductToCart;

class AddProductToCart
{
    public function __construct(public readonly string $cartId, public readonly string $productId) {}
}
