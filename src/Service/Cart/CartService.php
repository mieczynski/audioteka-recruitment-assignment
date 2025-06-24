<?php

namespace App\Service\Cart;

use App\Entity\Cart;
use App\Entity\Product;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CartService implements CartServiceInterface
{
    public function __construct(
        private readonly CartRepository $cartRepository,
        private readonly ProductRepository $productRepository,
    ) {}

    public function createCart(): Cart
    {
        $cart = new Cart(Uuid::uuid4()->toString());
        $this->cartRepository->save($cart);

        return $cart;
    }

    public function addProduct(string $cartId, string $productId): void
    {
        [$cart, $product] = $this->getCartAndProductOrFail($cartId, $productId);

        if (!$this->hasCartProduct($cart, $product) && $cart->isFull()) {
            throw new HttpException(Response::HTTP_UNPROCESSABLE_ENTITY, 'Cart is full.');
        }

        $cart->addProduct($product);
        $this->cartRepository->save($cart);
    }

    public function removeProduct(string $cartId, string $productId): void
    {
        [$cart, $product] = $this->getCartAndProductOrFail($cartId, $productId);

        $cart->removeProduct($product);
        $this->cartRepository->save($cart);
    }

    public function updateProductQuantity(string $cartId, string $productId, int $quantity = 1): void
    {
        [$cart, $product] = $this->getCartAndProductOrFail($cartId, $productId);

        if (!$this->hasCartProduct($cart, $product) && $cart->isFull()) {
            throw new HttpException(Response::HTTP_UNPROCESSABLE_ENTITY, 'Cart is full.');
        }

        $cart->updateProductQuantity($product, $quantity);
        $this->cartRepository->save($cart);
    }

    public function getCart(string $cartId): ?Cart
    {
        return $this->cartRepository->find($cartId);
    }

    private function getCartAndProductOrFail(string $cartId, string $productId): array
    {
        $cart = $this->cartRepository->findOneBy(['id' => $cartId]);
        $product = $this->productRepository->findOneBy(['id' => $productId]);

        if (!$cart || !$product) {
            throw new NotFoundHttpException('Cart or product not found.');
        }

        return [$cart, $product];
    }

    private function hasCartProduct(Cart $cart, Product $product): bool
    {
        return $cart->findCartProduct($product) !== null;
    }
}
