<?php

namespace App\Messenger;

class UpdateProductFromCatalog
{
    public function __construct(public readonly string $productId, public readonly string $name, public readonly float $price) {}
}