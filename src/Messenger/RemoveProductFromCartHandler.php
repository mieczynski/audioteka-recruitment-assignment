<?php

namespace App\Messenger;

use App\Service\Cart\CartServiceInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class RemoveProductFromCartHandler implements MessageHandlerInterface
{
    public function __construct(private readonly CartServiceInterface $cartService) { }

    public function __invoke(RemoveProductFromCart $command): void
    {
        $this->cartService->removeProduct($command->cartId, $command->productId);
    }
}
