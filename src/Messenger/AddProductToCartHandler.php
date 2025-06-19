<?php

namespace App\Messenger;

use App\Service\Cart\CartServiceInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class AddProductToCartHandler implements MessageHandlerInterface
{
    public function __construct(private readonly CartServiceInterface $cartService) { }

    public function __invoke(AddProductToCart $command): void
    {
        $this->cartService->addProduct($command->cartId, $command->productId);
    }
}