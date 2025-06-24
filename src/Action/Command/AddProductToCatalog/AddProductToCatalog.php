<?php

namespace App\Action\Command\AddProductToCatalog;

class AddProductToCatalog
{
    public function __construct(public readonly string $name, public readonly int $price) {}
}