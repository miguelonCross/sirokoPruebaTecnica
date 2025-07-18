<?php

declare(strict_types=1);

namespace App\Tests\UseCases\UpdateOrderStatusByUUIDTest;

use App\UseCase\UpdateOrderStatusByUUID\UpdateOrderStatusByUUID;
use App\UseCase\UpdateOrderStatusByUUID\UpdateOrderStatusByUUIDRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class UpdateOrderStatusByUUIDTest extends TestCase
{
    private string $mockOrdersJson;

    private UpdateOrderStatusByUUID $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockOrdersJson = file_get_contents(__DIR__.'/../../Mocks/orders.json');

        $this->useCase = new class($this->mockOrdersJson) extends UpdateOrderStatusByUUID {
            public function __construct(private string $path)
            {
            }

            private function getOrdersPath(): string
            {
                return $this->path;
            }
        };
    }

    public function testUpdateStatusOnExistingOrder(): void
    {
        $orderUuid = 'a5d6b55a-663f-4c30-99e3-93cf0d04a29d';
        $request = new UpdateOrderStatusByUUIDRequest(Uuid::fromString($orderUuid), 'SUCCESS');

        $response = $this->useCase->execute($request);

        $this->assertNotNull($response->order);
        $this->assertSame('SUCCESS', $response->order->status);
    }

    /** @test */
    public function testItDdesNotModifyAnythingWhenUUIDDoesNotExist(): void
    {
        $orderUuid = '00000000-0000-0000-0000-000000000000';
        $request = new UpdateOrderStatusByUUIDRequest(Uuid::fromString($orderUuid), 'SUCCESS');

        $response = $this->useCase->execute($request);

        $this->assertNull($response->order);
    }
}
