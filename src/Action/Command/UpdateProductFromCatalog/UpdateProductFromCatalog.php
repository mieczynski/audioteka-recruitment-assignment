<?php

namespace App\Action\Command\UpdateProductFromCatalog;

class UpdateProductFromCatalog
{
    public function __construct(public readonly string $productId, public readonly string $name, public readonly float $price) {}
}