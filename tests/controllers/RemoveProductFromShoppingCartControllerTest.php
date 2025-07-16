<?php

namespace App\Tests\controllers;

use App\Entity\ShoppingCartProduct;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class RemoveProductFromShoppingCartControllerTest extends WebTestCase
{
    public function test_givenEmptyFieldsShouldReturnBadRequest(): void
    {
        $client = static::createClient();
        $client->request('DELETE', '/shoppingCart/remove');
        $this->assertEquals(422, $client->getResponse()->getStatusCode());
    }

    public function test_givenEmptyFieldsShouldReturnOK(): void
    {
        $client = static::createClient();
        $container = static::getContainer();

        $clientUUID = '45659fae-eb17-4c11-b980-988b77c511bc';
        $testCache = new ArrayAdapter();
        $product = (new ShoppingCartProduct('my-code', 1, 1000))->toArray();
        $item = $testCache->getItem($clientUUID)->set([$product]);
        $testCache->save($item);

        $container->set('cache.app', $testCache);

        $client->request('DELETE', '/shoppingCart/remove', ['client_uuid' => '45659fae-eb17-4c11-b980-988b77c511bc', 'code' => 'my-code']);

        $this->assertEquals('{"message":"Product removed"}', $client->getResponse()->getContent());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function test_givenClientUUIDShoppingCartNotCreatedShouldReturnNotFound(): void
    {
        $client = static::createClient();
        $container = static::getContainer();

        $clientUUID = '45659fae-eb17-4c11-b980-988b77c511bc';
        $testCache = new ArrayAdapter();
        $product = (new ShoppingCartProduct('my-code', 1, 1000))->toArray();
        $item = $testCache->getItem($clientUUID)->set([$product]);
        $testCache->save($item);

        $container->set('cache.app', $testCache);

        $client->request('DELETE', '/shoppingCart/remove', ['client_uuid' => '45659fae-eb17-4c11-b980-988b77c511bc', 'code' => 'code']);

        $this->assertEquals('{"message":"Product not found in shopping cart"}', $client->getResponse()->getContent());
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function test_givenClientUUIDShoppingCartNotFoundShouldReturnNotFound(): void
    {
        $client = static::createClient();
        $container = static::getContainer();

        $clientUUID = '45659fae-eb17-4c11-b980-988b77c511bc';
        $testCache = new ArrayAdapter();
        $product = (new ShoppingCartProduct('my-code', 1, 1000))->toArray();
        $item = $testCache->getItem($clientUUID)->set([$product]);
        $testCache->save($item);

        $container->set('cache.app', $testCache);

        $client->request('DELETE', '/shoppingCart/remove', ['client_uuid' => '45659fae-eb17-4c11-b980-988b77c511b1', 'code' => 'code']);

        $this->assertEquals('{"message":"Shopping cart not found"}', $client->getResponse()->getContent());
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
