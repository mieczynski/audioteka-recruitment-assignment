<?php

namespace App\Action\Command\RemoveProductFromCatalog;

use App\Service\Catalog\ProductServiceInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class RemoveProductFromCatalogHandler implements MessageHandlerInterface
{
    public function __construct(private readonly ProductServiceInterface $productService) { }

    public function __invoke(RemoveProductFromCatalog $command): void
    {
        $this->productService->remove($command->productId);
    }
}
