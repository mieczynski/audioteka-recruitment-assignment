<?php

namespace App\Messenger;

use App\Service\Cart\CartServiceInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UpdateProductQuantityHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly CartServiceInterface $cartService,
    ) {}

    public function __invoke(UpdateProductQuantity $message): void
    {
       $this->cartService->updateProductQuantity($message->cartId, $message->productId, $message->quantity);
    }
}
