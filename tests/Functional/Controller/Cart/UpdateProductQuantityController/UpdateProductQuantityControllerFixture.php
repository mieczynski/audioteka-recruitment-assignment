<?php

namespace App\Tests\Functional\Controller\Cart\UpdateProductQuantityController;

use App\Entity\Cart;
use App\Entity\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class UpdateProductQuantityControllerFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $product1 = new Product('fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7', 'Product 1', 1990);
        $product2 = new Product('9670ea5b-d940-4593-a2ac-4589be784203', 'Product 2', 3990);
        $product3 = new Product('15e4a636-ef98-445b-86df-46e1cc0e10b5', 'Product 3', 4990);
        $product4 = new Product('00e91390-3af8-4735-bd06-0311e7131757', 'Product 4', 5990);

        foreach ([$product1, $product2, $product3, $product4] as $product) {
            $manager->persist($product);
        }

        $cart = new Cart('5bd88887-7017-4c08-83de-8b5d9abde58c');
        $manager->persist($cart);

        $fullCart = new Cart('1e82de36-23f3-4ae7-ad5d-616295f1d6c0');
        $fullCart->addProduct($product1, 2);
        $fullCart->addProduct($product2);
        $fullCart->addProduct($product3);
        $manager->persist($fullCart);

        $manager->flush();
    }
}
