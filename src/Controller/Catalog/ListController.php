<?php

namespace App\Controller\Catalog;

use App\ResponseBuilder\ProductListBuilder;
use App\Service\Catalog\ProductProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/products", methods={"GET"}, name="product-list")
 */
class ListController extends AbstractController
{

    public function __construct(private readonly ProductProvider $productProvider, private readonly ProductListBuilder $productListBuilder) { }

    public function __invoke(Request $request): Response
    {
        $products = $this->productProvider->getProducts();
        $totalCount = $this->productProvider->getTotalCount();

        return new JsonResponse(
            $this->productListBuilder->build($products, $totalCount),
            Response::HTTP_OK
        );
    }
}
