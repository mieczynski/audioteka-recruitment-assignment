<?php

namespace App\Service\Cart;

use App\Entity\Cart;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Ramsey\Uuid\Uuid;

class CartService implements CartServiceInterface
{
    public function __construct(
        private readonly CartRepository $cartRepository,
        private readonly ProductRepository $productRepository
    ) {}

    public function createCart(): Cart
    {
        $cart = new Cart(Uuid::uuid4()->toString());
        $this->cartRepository->save($cart);

        return $cart;
    }

    public function addProduct(string $cartId, string $productId): void
    {
        $cart = $this->cartRepository->findOneBy(['id' => $cartId]);
        $product = $this->productRepository->findOneBy(['id' => $productId]);

        if (!$cart || !$product) {
            throw new \InvalidArgumentException('Cart or product not found.');
        }

        $cart->addProduct($product);

        $this->cartRepository->save($cart);
    }

    public function removeProduct(string $cartId, string $productId): void
    {
        $cart = $this->cartRepository->findOneBy(['id' => $cartId]);
        $product = $this->productRepository->findOneBy(['id' => $productId]);

        if (!$cart || !$product) {
            throw new \InvalidArgumentException('Cart or product not found.');
        }

        $cart->removeProduct($product);

        $this->cartRepository->save($cart);
    }

    public function updateProductQuantity(string $cartId, string $productId, int $quantity = 1): void
    {
        $cart = $this->cartRepository->findOneBy(['id' => $cartId]);
        $product = $this->productRepository->findOneBy(['id' => $productId]);

        if (!$cart || !$product) {
            throw new \InvalidArgumentException('Cart or product not found.');
        }

        $cart->updateProductQuantity($product, $quantity);
        $this->cartRepository->save($cart);
    }

    public function getCart(string $cartId): ?Cart
    {
        return $this->cartRepository->find($cartId);
    }
}
