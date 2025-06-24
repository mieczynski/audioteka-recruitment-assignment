<?php

namespace App\Tests\Functional\Controller\Catalog\UpdateController;

use App\Tests\Functional\WebTestCase;

class UpdateControllerTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->loadFixtures(new UpdateControllerFixture());
    }

    public function test_updates_product_successfully(): void
    {
        $this->client->request('PUT', '/products/' . UpdateControllerFixture::PRODUCT_ID, [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'name' => 'Updated Product',
            'price' => 2590,
        ], JSON_THROW_ON_ERROR));

        self::assertResponseStatusCodeSame(202);

        $this->client->request('GET', '/products');
        self::assertResponseStatusCodeSame(200);

        $response = $this->getJsonResponse();
        self::assertCount(1, $response['products']);
        self::assertEquals('Updated Product', $response['products'][0]['name']);
        self::assertEquals(2590, $response['products'][0]['price']);
    }

    public function test_returns_404_for_nonexistent_product(): void
    {
        $this->client->request('PUT', '/products/00000000-0000-0000-0000-000000000000', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'name' => 'Name',
            'price' => 1000,
        ], JSON_THROW_ON_ERROR));

        self::assertResponseStatusCodeSame(404);
        $response = $this->getJsonResponse();
        self::assertEquals('Entity not found.', $response['error_message']);
    }

    public function test_cannot_update_with_invalid_name(): void
    {
        $this->client->request('PUT', '/products/' . UpdateControllerFixture::PRODUCT_ID, [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'name' => '   ',
            'price' => 1990,
        ], JSON_THROW_ON_ERROR));

        self::assertResponseStatusCodeSame(422);
    }

    public function test_cannot_update_with_invalid_price(): void
    {
        $this->client->request('PUT', '/products/' . UpdateControllerFixture::PRODUCT_ID, [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'name' => 'Valid Name',
            'price' => 0,
        ], JSON_THROW_ON_ERROR));

        self::assertResponseStatusCodeSame(422);
    }

    public function test_cannot_update_with_missing_fields(): void
    {
        $this->client->request('PUT', '/products/' . UpdateControllerFixture::PRODUCT_ID, [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([], JSON_THROW_ON_ERROR));

        self::assertResponseStatusCodeSame(422);
    }
}
