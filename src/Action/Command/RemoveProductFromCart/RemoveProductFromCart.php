<?php

namespace App\Action\Command\RemoveProductFromCart;

class RemoveProductFromCart
{
    public function __construct(public readonly string $cartId, public readonly string $productId) {}
}