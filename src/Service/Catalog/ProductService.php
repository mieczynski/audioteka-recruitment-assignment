<?php

namespace App\Service\Catalog;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Ramsey\Uuid\Uuid;

class ProductService implements ProductServiceInterface, ProductProvider
{
    public function __construct(private readonly ProductRepository $productRepository){}

    public function add(string $name, int $price): Product
    {
        $product = new Product(Uuid::uuid4(), $name, $price);
        $this->productRepository->save($product);
        return $product;
    }

    public function remove(string $id): void
    {
        $product = $this->productRepository->find($id);
        if ($product !== null) {
            $this->productRepository->remove($product);
        }
    }

    public function exists(string $productId): bool
    {
        return $this->productRepository->find($productId) !== null;
    }

    public function getProducts(): iterable
    {
        return $this->productRepository->findPaginated();
    }

    public function getTotalCount(): int
    {
        return $this->productRepository->getTotalCount();
    }
}
