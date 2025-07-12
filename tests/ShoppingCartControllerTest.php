<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ShoppingCartControllerTest extends WebTestCase
{
    public function test_givenEmptyFieldsShouldReturnBadRequest(): void
    {
        $client = static::createClient();
        $client->request('GET', '/shoppingCart',[]);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }
}
