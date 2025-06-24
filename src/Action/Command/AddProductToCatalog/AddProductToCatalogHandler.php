<?php

namespace App\Action\Command\AddProductToCatalog;

use App\Service\Catalog\ProductServiceInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class AddProductToCatalogHandler implements MessageHandlerInterface
{
    public function __construct(private readonly ProductServiceInterface $productService) { }

    public function __invoke(AddProductToCatalog $command): void
    {
        $this->productService->add($command->name, $command->price);
    }
}