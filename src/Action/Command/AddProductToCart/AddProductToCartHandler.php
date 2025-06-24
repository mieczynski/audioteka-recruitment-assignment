<?php

namespace App\Action\Command\AddProductToCart;

use App\Service\Cart\CartServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class AddProductToCartHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly CartServiceInterface $cartService,
        private readonly EntityManagerInterface $em
    ) {}

    public function __invoke(AddProductToCart $command): void
    {
        $this->em->wrapInTransaction(function () use ($command) {
            $this->cartService->addProduct($command->cartId, $command->productId);
        });
    }
}