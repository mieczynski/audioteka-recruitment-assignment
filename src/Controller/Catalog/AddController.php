<?php

namespace App\Controller\Catalog;

use App\Action\Command\AddProductToCatalog\AddProductToCatalog;
use App\Messenger\MessageBusAwareInterface;
use App\Messenger\MessageBusTrait;
use App\ResponseBuilder\ErrorBuilderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/products", methods={"POST"}, name="product-add")
 */
class AddController extends AbstractController implements MessageBusAwareInterface
{
    use MessageBusTrait;

    public function __construct(private readonly ErrorBuilderInterface $errorBuilder) { }

    public function __invoke(Request $request): Response
    {
        $name = trim($request->get('name'));
        $price = (int)$request->get('price');

        if ($name === '' || $price < 1) {
            return new JsonResponse(
                $this->errorBuilder->build('Invalid name or price.'),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $this->dispatch(new AddProductToCatalog($name, $price));

        return new Response('', Response::HTTP_ACCEPTED);
    }
}