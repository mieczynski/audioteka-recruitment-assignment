<?php

namespace App\Action\Command\UpdateProductQuantity;

use App\Service\Cart\CartServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UpdateProductQuantityHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly CartServiceInterface $cartService,
        private readonly EntityManagerInterface $em
    ) {}

    public function __invoke(UpdateProductQuantity $message): void
    {
        $this->em->wrapInTransaction(function () use ($message) {
            $this->cartService->updateProductQuantity(
                $message->cartId,
                $message->productId,
                $message->quantity
            );
        });
    }
}
