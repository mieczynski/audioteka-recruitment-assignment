<?php

namespace App\Service\Catalog;

interface ProductInterface
{
    public function getId(): string;
    public function getName(): string;
    public function getPrice(): int;
}
