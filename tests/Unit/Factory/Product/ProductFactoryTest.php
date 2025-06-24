<?php

namespace App\Tests\Unit\Factory\Product;

use App\Entity\Product;
use App\Factory\Product\ProductFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Factory\Product\ProductFactory
 */
class ProductFactoryTest extends TestCase
{
    private ProductFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new ProductFactory();
    }

    public function test_updates_product_properties(): void
    {
        $product = new Product('11111111-1111-1111-1111-111111111111', 'Old Name', 1000);

        $updated = $this->factory->update($product, 'New Name', 2500);

        $this->assertSame($product, $updated);
        $this->assertEquals('New Name', $updated->getName());
        $this->assertEquals(2500, $updated->getPrice());
    }
}
