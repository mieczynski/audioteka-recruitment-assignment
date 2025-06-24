<?php

namespace App\Controller\Cart;

use App\Action\Command\UpdateProductQuantity\UpdateProductQuantity;
use App\DTO\Cart\UpdateProductQuantityDTO;
use App\Entity\Cart;
use App\Entity\Product;
use App\Messenger\MessageBusTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/cart/{cart}/{product}", methods={"PATCH"}, name="cart-update-product-quantity")
 */
class UpdateProductQuantityController extends AbstractController
{
    use MessageBusTrait;

    public function __construct(
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

        $this->dispatch(new UpdateProductQuantity($cart->getId(), $product->getId(), $updateProductQuantityDTO->getQuantity()));
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
