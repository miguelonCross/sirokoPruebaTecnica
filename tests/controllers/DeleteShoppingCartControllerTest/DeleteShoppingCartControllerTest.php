<?php

declare(strict_types=1);

namespace App\Tests\controllers\DeleteShoppingCartControllerTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class DeleteShoppingCartControllerTest extends WebTestCase
{
    public function testGivenClientUUIDShouldReturnOK(): void
    {
        $client = static::createClient();
        $container = static::getContainer();

        $clientUUID = 'd8a9b999-65a7-41e3-b5ab-ddb30ef12d09';
        $testCache = new ArrayAdapter();

        $item = $testCache->getItem($clientUUID)->set(['uuid' => '2a90c5d1-efee-449c-8134-2b3968bd0de8', 'items' => []]);
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
