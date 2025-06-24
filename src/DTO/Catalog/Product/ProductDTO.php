<?php

namespace App\DTO\Catalog\Product;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ProductDTO
{
    #[Assert\NotNull(message: 'Price is required.')]
    #[Assert\Type(type: 'integer', message: 'Price must be an integer.')]
    #[Assert\Positive(
        message: 'Price must be positive.',
    )]
    public ?int $price =null ;

    #[Assert\Type(type: 'string', message: 'Name must be an string.')]
    #[Assert\NotBlank(message: 'Name is required.')]
    public ?string $name = null;

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        if (trim((string)$this->name) === '') {
            $context
                ->buildViolation('Name cannot be empty or only whitespace.')
                ->atPath('name')
                ->addViolation();
        }
    }
}