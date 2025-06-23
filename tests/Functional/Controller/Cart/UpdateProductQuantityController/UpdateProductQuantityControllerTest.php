<?php

namespace App\Tests\Functional\Controller\Cart\UpdateProductQuantityController;

use App\Tests\Functional\WebTestCase;

class UpdateProductQuantityControllerTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures(new UpdateProductQuantityControllerFixture());
    }

    public function test_increases_quantity_of_existing_product(): void
    {
        $this->client->request(
            'PATCH',
            '/cart/5bd88887-7017-4c08-83de-8b5d9abde58c/fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['quantity' => 2])
        );

        self::assertResponseStatusCodeSame(204);

        $this->client->request('GET', '/cart/5bd88887-7017-4c08-83de-8b5d9abde58c');
        $response = $this->getJsonResponse();

        self::assertEquals(2, $response['products'][0]['quantity']);
    }

    public function test_decreases_quantity_of_existing_product(): void
    {
        $this->client->request(
            'PATCH',
            '/cart/1e82de36-23f3-4ae7-ad5d-616295f1d6c0/fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['quantity' => -1])
        );

        self::assertResponseStatusCodeSame(204);

        $this->client->request('GET', '/cart/1e82de36-23f3-4ae7-ad5d-616295f1d6c0');
        $response = $this->getJsonResponse();

        self::assertEquals(1, $response['products'][0]['quantity']);
    }

    public function test_removes_product_when_quantity_becomes_zero(): void
    {
        $this->client->request(
            'PATCH',
            '/cart/5bd88887-7017-4c08-83de-8b5d9abde58c/fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['quantity' => -1])
        );

        self::assertResponseStatusCodeSame(204);

        $this->client->request('GET', '/cart/5bd88887-7017-4c08-83de-8b5d9abde58c');
        $response = $this->getJsonResponse();

        self::assertCount(0, $response['products']);
    }

    public function test_rejects_quantity_above_limit(): void
    {
        $this->client->request(
            'PATCH',
            '/cart/5bd88887-7017-4c08-83de-8b5d9abde58c/fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['quantity' => 101])
        );

        self::assertResponseStatusCodeSame(422);
        $response = $this->getJsonResponse();

        self::assertArrayHasKey('error', $response);
    }

    public function test_returns_404_if_cart_does_not_exist(): void
    {
        $this->client->request(
            'PATCH',
            '/cart/00000000-0000-0000-0000-000000000000/fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['quantity' => 1])
        );

        self::assertResponseStatusCodeSame(404);
    }

    public function test_returns_404_if_product_does_not_exist(): void
    {
        $this->client->request(
            'PATCH',
            '/cart/5bd88887-7017-4c08-83de-8b5d9abde58c/00000000-0000-0000-0000-000000000000',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['quantity' => 1])
        );

        self::assertResponseStatusCodeSame(404);
    }

    public function test_rejects_non_integer_quantity(): void
    {
        $this->client->request(
            'PATCH',
            '/cart/5bd88887-7017-4c08-83de-8b5d9abde58c/fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['quantity' => 'abc'])
        );

        self::assertResponseStatusCodeSame(400);
        $response = $this->getJsonResponse();

        self::assertArrayHasKey('error_message', $response);
    }
}
