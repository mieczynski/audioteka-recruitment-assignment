<?php

namespace App\ResponseBuilder;

use App\Entity\CartProducts;
use App\Service\Cart\CartInterface;

class CartBuilder
{
    public function __invoke(CartInterface $cart): array
    {
        $data = [
            'total_price' => $cart->getTotalPrice(),
            'products' => []
        ];

        /** @var CartProducts $cartProducts */
        foreach ($cart->getProducts() as $cartProducts) {
            $product = $cartProducts->getProduct();

            $data['products'][] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'quantity' => $cartProducts->getQuantity(),
            ];
        }


        return $data;
    }
}
