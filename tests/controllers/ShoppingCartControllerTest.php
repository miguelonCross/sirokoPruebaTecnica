<?php

namespace App\Tests\controllers;

use App\dto\ShoppingCartDTO;
use App\Entity\Product;
use App\Entity\ShoppingCartItem;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Uid\Uuid;

class ShoppingCartControllerTest extends WebTestCase
{
    public function test_givenEmptyFieldsShouldReturnBadRequest(): void
    {
        $client = static::createClient();
        $client->request('POST', '/shoppingCart');
        $this->assertEquals(422, $client->getResponse()->getStatusCode());
    }
    public function test_givenClientUUIDShouldReturnOK(): void
    {
        $client = static::createClient();
        $container = static::getContainer();

        $clientUUID = '45659fae-eb17-4c11-b980-988b77c511bc';
        $testCache = new ArrayAdapter();

        $product = new Product(new Uuid('2a90c5d1-efee-449c-8134-2b3968bd0de8'), 'Casco Asic', 1099, 'Casco ciclista de Asics', 'cyclism');

        $shoppingCartItem = new ShoppingCartItem($product, 2);

        $shoppingCart = new ShoppingCartDTO('2a90c5d1-efee-449c-8134-2b3968bd0de8', [$shoppingCartItem->toArray()]);

        $item = $testCache->getItem($clientUUID)->set(['uuid' => '2a90c5d1-efee-449c-8134-2b3968bd0de8', 'products' =>  [$shoppingCartItem->toArray()]]);
        $testCache->save($item);
        $container->set('cache.app', $testCache);

        $client->request(
            'POST',
            '/shoppingCart',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['client_uuid'=> $clientUUID])
        );

        $body = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($shoppingCart->toArray(), $body);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
