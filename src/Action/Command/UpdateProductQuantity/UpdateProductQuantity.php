<?php

namespace App\Action\Command\UpdateProductQuantity;

class UpdateProductQuantity
{
    public function __construct(
        public readonly string $cartId,
        public readonly string $productId,
        public readonly int $quantity
    ) {}
}
