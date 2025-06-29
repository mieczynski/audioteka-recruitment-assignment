<?php

namespace App\Tests\Functional\Controller\Cart\ShowCartController;

use App\Tests\Functional\WebTestCase;

class ShowCartControllerTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures(new ShowCartControllerFixture());
    }

    private function sortProductsById(array $products): array
    {
        usort($products, fn($a, $b) => strcmp($a['id'], $b['id']));
        return $products;
    }

    public function test_shows_cart(): void
    {
        $this->client->request('GET', '/cart/fab8f7c3-a641-43c1-92d3-ee871a55fa8a');
        self::assertResponseStatusCodeSame(200);

        $response = $this->getJsonResponse();

        $expectedProducts = [
            [
                'id' => '15e4a636-ef98-445b-86df-46e1cc0e10b5',
                'name' => 'Product 3',
                'price' => 4990,
                'quantity' => 1
            ],
            [
                'id' => '9670ea5b-d940-4593-a2ac-4589be784203',
                'name' => 'Product 2',
                'price' => 3990,
                'quantity' => 1
            ],
            [
                'id' => 'fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7',
                'name' => 'Product 1',
                'price' => 1990,
                'quantity' => 1
            ],
        ];

        self::assertEquals(10970, $response['total_price']);
        self::assertEquals(
            $this->sortProductsById($expectedProducts),
            $this->sortProductsById($response['products'])
        );
    }

    public function test_returns_404_if_cart_does_not_exist(): void
    {
        $this->client->request('GET', '/cart/2d6b5d93-e1fd-4f69-8293-832497be09cd');
        self::assertResponseStatusCodeSame(404);
    }
}