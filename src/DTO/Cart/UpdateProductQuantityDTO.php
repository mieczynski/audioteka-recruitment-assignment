<?php

namespace App\DTO\Cart;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateProductQuantityDTO
{
    #[Assert\NotNull(message: 'Quantity is required.')]
    #[Assert\Type(type: 'integer', message: 'Quantity must be an integer.')]
    #[Assert\Range(
        notInRangeMessage: 'Quantity must be between -100 and 100.',
        min: -100,
        max: 100
    )]
    public ?int $quantity = null;

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }
}