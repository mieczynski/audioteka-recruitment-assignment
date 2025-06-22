<?php

namespace App\Messenger;

use App\Service\Cart\CartInterface;
use App\Service\Cart\CartServiceInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateCartHandler implements MessageHandlerInterface
{
    public function __construct(private readonly CartServiceInterface $cartService) { }

    public function __invoke(CreateCart $command): CartInterface
    {
        return $this->cartService->createCart();
    }
}
