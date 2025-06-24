<?php

namespace App\Controller\Catalog;

use App\DTO\Catalog\Product\ProductDTO;
use App\Entity\Product;
use App\Messenger\MessageBusAwareInterface;
use App\Messenger\MessageBusTrait;
use App\Messenger\UpdateProductFromCatalog;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/products/{product}", methods={"PUT"}, name="product-update")
 */
class UpdateController extends AbstractController implements MessageBusAwareInterface
{
    use MessageBusTrait;

    public function __construct(
        private readonly ValidatorInterface $validator,
    ) {}

    /**
     * @ParamConverter("productDTO", class="App\DTO\Catalog\Product\ProductDTO", converter="fos_rest.request_body")
     */
    public function __invoke(?Product $product, ProductDTO $productDTO): Response
    {
        if (!$product) {
            throw new NotFoundHttpException('Product not found.');
        }

        $violations = $this->validator->validate($productDTO);
        if (count($violations) > 0) {
            throw new ValidationFailedException($productDTO, $violations);
        }

        $this->dispatch(new UpdateProductFromCatalog(
            $product->getId(),
            $productDTO->getName(),
            $productDTO->getPrice()
        ));

        return new Response('', Response::HTTP_ACCEPTED);
    }
}
