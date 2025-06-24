<?php

namespace App\Action\Command\UpdateProductFromCatalog;

use App\Service\Catalog\ProductServiceInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UpdateProductFromCatalogHandler implements MessageHandlerInterface
{
    public function __construct(private readonly ProductServiceInterface $productService) { }

    public function __invoke(UpdateProductFromCatalog $command): void
    {
        $this->productService->update($command->productId, $command->name, $command->price);
    }
}