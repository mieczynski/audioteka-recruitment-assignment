<?php

namespace App\DTO\Cart;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateProductQuantityDTO
{
    #[Assert\NotNull(message: 'Quantity is required.')]
    #[Assert\Type(type: 'integer', message: 'Quantity must be an integer.')]
    public ?int $quantity = null;

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }
}