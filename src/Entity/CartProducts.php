<?php

namespace App\Entity;

use App\Service\Cart\CartInterface;
use App\Service\Catalog\ProductInterface;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
#[ORM\Table(name: 'cart_products')]
class CartProducts
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Cart::class, inversedBy: 'cartProducts')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private CartInterface $cart;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ProductInterface $product;

    #[ORM\Column(type: 'integer')]
    private int $quantity = 1;

    public function __construct(CartInterface $cart, ProductInterface $product, int $quantity = 1)
    {
        $this->cart = $cart;
        $this->product = $product;
        $this->quantity = $quantity;
    }

    public function getCart(): CartInterface
    {
        return $this->cart;
    }

    public function setCart(CartInterface $cart): self
    {
        $this->cart = $cart;
        return $this;
    }

    public function getProduct(): ProductInterface
    {
        return $this->product;
    }

    public function setProduct(ProductInterface $product): self
    {
        $this->product = $product;
        return $this;
    }

    public function increaseQuantity(int $amount = 1): void
    {
        $this->quantity += $amount;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }
}
