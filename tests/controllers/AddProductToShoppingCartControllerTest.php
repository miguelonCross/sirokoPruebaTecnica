<?php

namespace App\Tests\controllers;

use App\Entity\ShoppingCartProduct;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class AddProductToShoppingCartControllerTest extends WebTestCase
{
    public function test_givenEmptyFieldsShouldReturnUnprocessableContent(): void
    {
        $client = static::createClient();
        $client->request('POST', '/shoppingCart/add');
        $this->assertEquals(422, $client->getResponse()->getStatusCode());
    }
    public function test_givenClientUUIDShouldReturnOK(): void
    {
        $client = static::createClient();
        $container = static::getContainer();

        $clientUUID = 'd8a9b999-65a7-41e3-b5ab-ddb30ef12d09';
        $testCache = new ArrayAdapter();
        $product = (new ShoppingCartProduct('my-code', 1, 1000))->toArray();
        $item = $testCache->getItem($clientUUID)->set([$product]);
        $testCache->save($item);

        $container->set('cache.app', $testCache);

        $body = json_encode(['client_uuid' => 'd8a9b999-65a7-41e3-b5ab-ddb30ef12d09', 'product' => $product]);
        $client->request(
            'POST',
            '/shoppingCart/add',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $body
        );

        $this->assertEquals([$product], json_decode($client->getResponse()->getContent(), true));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
