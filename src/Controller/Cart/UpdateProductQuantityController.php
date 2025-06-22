<?php

namespace App\Controller\Cart;

use App\DTO\Cart\UpdateProductQuantityDTO;
use App\Entity\Cart;
use App\Entity\Product;
use App\Messenger\UpdateProductQuantity;
use App\ResponseBuilder\ErrorBuilderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/cart/{cart}/{product}', name: 'cart-update-product-quantity', methods: ['PATCH'])]
class UpdateProductQuantityController extends AbstractController
{
    use HandleTrait;

//    use MessageBusTrait;

    public function __construct(
        private readonly ErrorBuilderInterface $errorBuilder,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        MessageBusInterface $messageBus) {
        $this->messageBus = $messageBus;
    }


    public function __invoke(Cart $cart, Product $product, Request $request): Response
    {
        /** @var UpdateProductQuantityDTO $dto */
        $dto = $this->serializer->deserialize($request->getContent(), UpdateProductQuantityDTO::class, 'json');
        $violations = $this->validator->validate($dto);

        if (count($violations) > 0) {
            throw new ValidationFailedException($dto, $violations);
        }

        try {
            $this->handle(new UpdateProductQuantity($cart->getId(), $product->getId(), $dto->quantity));
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(
                ($this->errorBuilder)($e->getMessage()),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return new Response('', Response::HTTP_NO_CONTENT);
    }

}
