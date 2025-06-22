<?php

namespace App\Messenger;

class UpdateProductQuantity
{
    public function __construct(
        public readonly string $cartId,
        public readonly string $productId,
        public readonly int $quantity
    ) {}
}
