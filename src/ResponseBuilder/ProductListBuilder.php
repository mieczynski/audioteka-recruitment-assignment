<?php

namespace App\ResponseBuilder;

use App\Enum\FilterEnum;
use App\Service\Catalog\ProductInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProductListBuilder
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly RequestStack $requestStack
    ) {}

    /**
     * @param ProductInterface[] $products
     */
    public function build(iterable $products, int $totalCount): array
    {
        $page = $this->getPage();

        return [
            'previous_page' => $this->getPreviousPageUrl($page),
            'next_page' => $this->getNextPageUrl($page, $totalCount),
            'count' => $totalCount,
            'products' => $this->buildProductList($products),
        ];
    }

    private function getPage(): int
    {
        $request = $this->requestStack->getCurrentRequest();
        return max(0, (int) $request?->query->get('page', 0));
    }

    private function getPreviousPageUrl(int $page): ?string
    {
        return $page > 0
            ? $this->urlGenerator->generate('product-list', ['page' => $page - 1])
            : null;
    }

    private function getNextPageUrl(int $page, int $totalCount): ?string
    {
        $lastPage = (int) ceil($totalCount / FilterEnum::DEFAULT_PAGE_LIMIT);
        return $page < $lastPage - 1
            ? $this->urlGenerator->generate('product-list', ['page' => $page + 1])
            : null;
    }

    private function buildProductList(iterable $products): array
    {
        $output = [];
        foreach ($products as $product) {
            $output[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
            ];
        }

        return $output;
    }
}
