<?php

declare(strict_types=1);

namespace App\Tests\controllers\AddProductToShoppingCartControllerTest;

use App\Entity\Product;
use App\Entity\ShoppingCartItem;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Uid\Uuid;

class AddProductToShoppingCartControllerTest extends WebTestCase
{
    public function testGivenEmptyFieldsShouldReturnUnprocessableContent(): void
    {
        $client = static::createClient();
        $client->request('POST', '/shoppingCart/add');
        $this->assertEquals(422, $client->getResponse()->getStatusCode());
    }

    public function testGivenClientUUIDShouldReturnOK(): void
    {
        $client = static::createClient();
        $container = static::getContainer();

        $clientUUID = 'd8a9b999-65a7-41e3-b5ab-ddb30ef12d09';
        $testCache = new ArrayAdapter();

        $product = new Product(new Uuid('2a90c5d1-efee-449c-8134-2b3968bd0de8'), 'Casco Asic', 1099, 'Casco ciclista de Asics', 'cyclism');
        $shoppingCartItem = new ShoppingCartItem($product, 2);

        $item = $testCache->getItem($clientUUID)->set(['uuid' => '2a90c5d1-efee-449c-8134-2b3968bd0de8', 'items' => []]);
        $testCache->save($item);

        $container->set('cache.app', $testCache);

        $body = (string) json_encode(['client_uuid' => $clientUUID, 'product_uuid' => $product->uuid->toRfc4122(), 'quantity' => 2]);
        $client->request(
            'POST',
            '/shoppingCart/add',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $body
        );

        $response = json_decode((string) $client->getResponse()->getContent(), true);

        $this->assertEquals([$shoppingCartItem->toArray()], $response['items']);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAddingExistingProductShouldUpdateQuantity(): void
    {
        $client = static::createClient();
        $container = static::getContainer();

        $clientUUID = 'd8a9b999-65a7-41e3-b5ab-ddb30ef12d09';
        $testCache = new ArrayAdapter();

        $product = new Product(new Uuid('2a90c5d1-efee-449c-8134-2b3968bd0de8'), 'Casco Asic', 1099, 'Casco ciclista de Asics', 'cyclism');
        $shoppingCartItem = new ShoppingCartItem($product, 3);

        $item = $testCache->getItem($clientUUID)->set(['uuid' => '2a90c5d1-efee-449c-8134-2b3968bd0de8', 'items' => []]);
        $testCache->save($item);

        $container->set('cache.app', $testCache);

        $body = (string) json_encode(['client_uuid' => $clientUUID, 'product_uuid' => $product->uuid->toRfc4122(), 'quantity' => 3]);
        $client->request(
            'POST',
            '/shoppingCart/add',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $body
        );

        $response = json_decode((string) $client->getResponse()->getContent(), true);

        $this->assertEquals([$shoppingCartItem->toArray()], $response['items']);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
