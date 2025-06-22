<?php

namespace App\Controller\Cart;

use App\DTO\Cart\UpdateProductQuantityDTO;
use App\Entity\Cart;
use App\Entity\Product;
use App\Messenger\UpdateProductQuantity;
use App\ResponseBuilder\ErrorBuilderInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/cart/{cart}/{product}', name: 'cart-update-product-quantity', methods: ['PATCH'])]
class UpdateProductQuantityController extends AbstractController
{
    use HandleTrait;

    public function __construct(
        private readonly ErrorBuilderInterface $errorBuilder,
        private readonly ValidatorInterface $validator,
        MessageBusInterface $messageBus
    ) {
        $this->messageBus = $messageBus;
    }

    /**
     * @ParamConverter("updateProductQuantityDTO", class="App\DTO\Cart\UpdateProductQuantityDTO", converter="fos_rest.request_body")
     */
    public function __invoke(UpdateProductQuantityDTO $updateProductQuantityDTO, Cart $cart, Product $product): Response
    {
        $violations = $this->validator->validate($updateProductQuantityDTO);

        if (count($violations) > 0) {
            throw new ValidationFailedException($updateProductQuantityDTO, $violations);
        }

        try {
            $this->handle(new UpdateProductQuantity(
                $cart->getId(),
                $product->getId(),
                $updateProductQuantityDTO->getQuantity()
            ));
        } catch (\InvalidArgumentException|\DomainException $e) {
            return new JsonResponse(
                ($this->errorBuilder)($e->getMessage()),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
