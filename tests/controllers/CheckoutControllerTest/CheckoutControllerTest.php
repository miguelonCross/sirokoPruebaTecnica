<?php

declare(strict_types=1);

namespace App\Tests\controllers\CheckoutControllerTest;

use App\UseCase\GetOrderByUUID\GetOrderByUUID;
use App\UseCase\GetOrderByUUID\GetOrderByUUIDRequest;
use App\UseCase\GetOrderByUUID\GetOrderByUUIDResponse;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Uid\Uuid;

class CheckoutControllerTest extends WebTestCase
{
    public function testOrderNotFoundShouldReturnNotFound(): void
    {
        $client = static::createClient();

        $useCase = $this->createMock(GetOrderByUUID::class);
        $useCase
            ->expects($this->once())
            ->method('execute')
            ->with(new GetOrderByUUIDRequest(new Uuid('125ede61-b9ef-4c5f-ac50-20cb3ae6e8c1')))
            ->willReturn(new GetOrderByUUIDResponse(null));

        self::getContainer()->set(GetOrderByUUID::class, $useCase);

        $body = (string) json_encode(['order_uuid' => '125ede61-b9ef-4c5f-ac50-20cb3ae6e8c1', 'pan' => '4111111111111111', 'cvv' => '222', 'expires_date' => '12/34', 'holder' => 'Miguel C', 'address' => 'C LA Nº10']);

        $client->request(
            'POST',
            '/checkout',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $body
        );
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
