<?php

namespace App\Messenger;

use App\Service\Cart\CartServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class RemoveProductFromCartHandler implements MessageHandlerInterface
{
    public function __construct(private readonly CartServiceInterface $cartService,
            private readonly EntityManagerInterface $em) {}

    public function __invoke(RemoveProductFromCart $command): void
    {
        $this->em->wrapInTransaction(function () use ($command) {
            $this->cartService->removeProduct($command->cartId, $command->productId);
        });
    }
}
