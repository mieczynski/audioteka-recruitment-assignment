<?php

namespace App\Controller\Cart;

use App\Entity\Cart;
use App\Entity\Product;
use App\Messenger\AddProductToCart;
use App\Messenger\MessageBusTrait;
use App\ResponseBuilder\ErrorBuilderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cart/{cart}/{product}", methods={"PUT"}, name="cart-add-product")
 */
class AddProductController extends AbstractController
{
    use MessageBusTrait;

    public function __construct(private readonly ErrorBuilderInterface $errorBuilder, MessageBusInterface $messageBus) {
        $this->messageBus = $messageBus;
    }

    public function __invoke(Cart $cart, Product $product): Response
    {
        if ($cart->isFull()) {
            return new JsonResponse(
                $this->errorBuilder->build('Cart is full.'),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $this->dispatch(new AddProductToCart($cart->getId(), $product->getId()));

        return new Response('', Response::HTTP_ACCEPTED);
    }
}
