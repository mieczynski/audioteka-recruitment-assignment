<?php

namespace App\Entity;

use App\Service\Cart\CartInterface;
use App\Service\Catalog\ProductInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
class Cart implements CartInterface
{
    public const CAPACITY = 3;
    public const MAX_QUANTITY_PER_PRODUCT = 100;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', nullable: false)]
    private UuidInterface $id;

    /**
     * @var Collection<CartProducts>
     */
    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: CartProducts::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $products;

    public function __construct(string $id)
    {
        $this->id = Uuid::fromString($id);
        $this->products = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id->toString();
    }

    public function getTotalPrice(): int
    {
        return array_reduce(
            $this->products->toArray(),
            static fn(int $total, CartProducts $cartProduct): int =>
                $total + ($cartProduct->getProduct()->getPrice() * $cartProduct->getQuantity()),
            0
        );
    }

    #[Pure]
    public function isFull(): bool
    {
        return $this->products->count() >= self::CAPACITY;
    }

    public function getProducts(): iterable
    {
        return $this->products->getIterator();
    }


    public function addProduct(ProductInterface $product): void
    {
        foreach ($this->products as $cartProduct) {
            if ($cartProduct->getProduct() === $product) {
                return;
            }
        }

        $cartProduct = new CartProducts($this, $product, 1);
        $this->products->add($cartProduct);
    }



    public function removeProduct(ProductInterface $product): void
    {
        foreach ($this->products as $cartProduct) {
            if ($cartProduct->getProduct() === $product) {
                $this->products->removeElement($cartProduct);
                return;
            }
        }
    }


    public function updateProductQuantity(ProductInterface $product, int $quantity): void
    {
        if ($quantity < 1 || $quantity > self::MAX_QUANTITY_PER_PRODUCT) {
            throw new \InvalidArgumentException(sprintf(
                'Quantity must be between 1 and %d.',
                self::MAX_QUANTITY_PER_PRODUCT
            ));
        }

        foreach ($this->products as $cartProduct) {
            if ($cartProduct->getProduct() === $product) {
                $cartProduct->setQuantity($quantity);
                return;
            }
        }

        $cartProduct = new CartProducts($this, $product, $quantity);
        $this->products->add($cartProduct);
    }
}
