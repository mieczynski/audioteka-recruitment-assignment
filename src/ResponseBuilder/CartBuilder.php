<?php

namespace App\ResponseBuilder;

use App\Service\Cart\CartInterface;

class CartBuilder
{
    public function __invoke(CartInterface $cart): array
    {
        $data = [
            'total_price' => $cart->getTotalPrice(),
            'products' => []
        ];

        foreach ($cart->getProducts() as $product) {
            $data['products'][] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice()
            ];
        }

        return $data;
    }
}
