<?php

declare(strict_types=1);

namespace App\Tests\controllers\DeleteShoppingCartControllerTest;

use App\Entity\ShoppingCart;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Uid\UuidV4;

class DeleteShoppingCartControllerTest extends WebTestCase
{
    public function testGivenClientUUIDShouldReturnOK(): void
    {
        $client = static::createClient();
        $container = static::getContainer();

        $clientUUID = 'd8a9b999-65a7-41e3-b5ab-ddb30ef12d09';
        $testCache = new ArrayAdapter();

        $item = $testCache->getItem($clientUUID)->set(new ShoppingCart(UuidV4::v4(), []));
        $testCache->save($item);

        $container->set('cache.app', $testCache);

        $body = (string) json_encode(['client_uuid' => $clientUUID]);
        $client->request(
            'DELETE',
            '/shoppingCart',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $body
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
