<?php

namespace App\Controller\Cart;

use App\Entity\Cart;
use App\Entity\Product;
use App\Messenger\AddProductToCart;
use App\Messenger\MessageBusAwareInterface;
use App\Messenger\MessageBusTrait;
use App\ResponseBuilder\ErrorBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cart/{cart}/{product}", methods={"PUT"}, name="cart-add-product")
 */
class AddProductController extends AbstractController
{
    use HandleTrait;

//    use MessageBusTrait;

    public function __construct(private ErrorBuilder $errorBuilder, MessageBusInterface $messageBus) {
        $this->messageBus = $messageBus;
    }

    public function __invoke(Cart $cart, Product $product): Response
    {
        if ($cart->isFull()) {
            return new JsonResponse(
                $this->errorBuilder->__invoke('Cart is full.'),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $cart = $this->handle(new AddProductToCart($cart->getId(), $product->getId()));
//        $this->dispatch(new AddProductToCart($cart->getId(), $product->getId()));

        return new Response('', Response::HTTP_ACCEPTED);
    }
}
