<?php

namespace App\Tests\Functional\Controller\Catalog\UpdateController;

use App\Entity\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class UpdateControllerFixture extends AbstractFixture
{

    public const PRODUCT_ID = '0d46b18e-4620-4519-8640-e62ef81b92ec';

    public function load(ObjectManager $manager): void
    {
        $product = new Product(self::PRODUCT_ID, 'Original Product', 1990);
        $manager->persist($product);

        $manager->flush();
    }
}