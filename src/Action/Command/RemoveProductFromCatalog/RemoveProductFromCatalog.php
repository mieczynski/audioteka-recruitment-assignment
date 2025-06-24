<?php

namespace App\Action\Command\RemoveProductFromCatalog;

class RemoveProductFromCatalog
{
    public function __construct(public readonly string $productId) {}
}