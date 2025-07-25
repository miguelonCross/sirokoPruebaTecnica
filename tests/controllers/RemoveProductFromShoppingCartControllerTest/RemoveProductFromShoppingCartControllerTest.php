<?php

declare(strict_types=1);

namespace App\Tests\controllers\RemoveProductFromShoppingCartControllerTest;

use App\Entity\Product;
use App\Entity\ShoppingCart;
use App\Entity\ShoppingCartItem;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

class RemoveProductFromShoppingCartControllerTest extends WebTestCase
{
    public function testGivenEmptyFieldsShouldReturnBadRequest(): void
    {
        $client = static::createClient();
        $client->request('DELETE', '/shoppingCart/remove');
        $this->assertEquals(422, $client->getResponse()->getStatusCode());
    }

    public function testGivenEmptyFieldsShouldReturnOK(): void
    {
        $client = static::createClient();
        $container = static::getContainer();

        $clientUUID = '45659fae-eb17-4c11-b980-988b77c511bc';
        $testCache = new ArrayAdapter();
        $product = new Product(new Uuid('2a90c5d1-efee-449c-8134-2b3968bd0de8'), 'Casco Asic', 1099, 'Casco ciclista de Asics', 'cyclism');
        $shoppingCartItem = new ShoppingCartItem($product, 2);

        $item = $testCache->getItem($clientUUID)->set(new ShoppingCart(UuidV4::v4(), [$shoppingCartItem]));
        $testCache->save($item);
        $container->set('cache.app', $testCache);

        $client->request('DELETE', '/shoppingCart/remove', ['client_uuid' => '45659fae-eb17-4c11-b980-988b77c511bc', 'product_uuid' => $product->uuid->toRfc4122()]);

        $this->assertEquals('{"message":"Product removed"}', $client->getResponse()->getContent());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGivenClientUUIDShoppingCartNotCreatedShouldReturnNotFound(): void
    {
        $client = static::createClient();
        $container = static::getContainer();

        $clientUUID = '45659fae-eb17-4c11-b980-988b77c511bc';
        $testCache = new ArrayAdapter();

        $item = $testCache->getItem($clientUUID)->set(new ShoppingCart(UuidV4::v4(), []));

        $testCache->save($item);
        $container->set('cache.app', $testCache);

        $client->request('DELETE', '/shoppingCart/remove', ['client_uuid' => '45659fae-eb17-4c11-b980-988b77c511bc', 'product_uuid' => '2a90c5d1-efee-449c-8134-2b3968bd0de7']);

        $this->assertEquals('{"message":"Product not found in shopping cart"}', $client->getResponse()->getContent());
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testGivenClientUUIDShoppingCartNotFoundShouldReturnNotFound(): void
    {
        $client = static::createClient();
        $container = static::getContainer();

        $clientUUID = '45659fae-eb17-4c11-b980-988b77c511bc';
        $testCache = new ArrayAdapter();

        $item = $testCache->getItem($clientUUID)->set(new ShoppingCart(UuidV4::v4(), []));
        $testCache->save($item);
        $container->set('cache.app', $testCache);

        $client->request('DELETE', '/shoppingCart/remove', ['client_uuid' => '45659fae-eb17-4c11-b980-988b77c511b1', 'product_uuid' => '2a90c5d1-efee-449c-8134-2b3968bd0de7']);

        $this->assertEquals('{"message":"Shopping cart not found"}', $client->getResponse()->getContent());
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
