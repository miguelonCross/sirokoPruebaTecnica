<?php

declare(strict_types=1);

namespace App\Tests\controllers\GenerateOrderControllerTest;

use App\dto\ShoppingCartDTO;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\ShoppingCart;
use App\Entity\ShoppingCartItem;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

class GenerateOrderControllerTest extends WebTestCase
{
    public function testGivenClientUUIDShouldReturnOK(): void
    {
        $client = static::createClient();
        $container = static::getContainer();

        $clientUUID = '45659fae-eb17-4c11-b980-988b77c511bc';
        $testCache = new ArrayAdapter();

        $product = new Product(new Uuid('2a90c5d1-efee-449c-8134-2b3968bd0de8'), 'Casco Asic', 1099, 'Casco ciclista de Asics', 'cyclism');

        $shoppingCartItem = new ShoppingCartItem($product, 2);

        $shoppingCart = new ShoppingCartDTO('2a90c5d1-efee-449c-8134-2b3968bd0de8', [$shoppingCartItem]);

        $item = $testCache->getItem($clientUUID)->set(new ShoppingCart(UuidV4::v4(), [$shoppingCartItem]));
        $testCache->save($item);
        $container->set('cache.app', $testCache);

        $order = new Order(new Uuid('2a90c5d1-efee-449c-8134-2b3968bd0de8'), 2198, new Uuid($clientUUID), new ShoppingCart(new UuidV4('2a90c5d1-efee-449c-8134-2b3968bd0de8'), [$shoppingCartItem]), 'CREATED');

        $body = (string) json_encode(['client_uuid' => $clientUUID]);
        $client->request(
            'POST',
            '/createOrder',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $body
        );

        $response = json_decode((string) $client->getResponse()->getContent(), true);

        $this->assertEquals($order->status, $response[0]['status']);
        $this->assertEquals($shoppingCart->toArray()['items'], $response[0]['shopping_cart']['items']);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGivenClientUUIDWithEmptyCartShouldReturnBadRequest(): void
    {
        $client = static::createClient();
        $container = static::getContainer();

        $clientUUID = '45659fae-eb17-4c11-b980-988b77c511bc';
        $testCache = new ArrayAdapter();

        $item = $testCache->getItem($clientUUID)->set(new ShoppingCart(UuidV4::v4(), []));
        $testCache->save($item);
        $container->set('cache.app', $testCache);

        $body = (string) json_encode(['client_uuid' => '45659fae-eb17-4c11-b980-988b77c511b1']);
        $client->request(
            'POST',
            '/createOrder',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $body
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('{"error":"Shopping cart is empty"}', $client->getResponse()->getContent());
    }
}
