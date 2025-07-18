<?php

declare(strict_types=1);

namespace App\Tests\UseCases\GetOrderByUUIDTest;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\ShoppingCart;
use App\Entity\ShoppingCartItem;
use App\UseCase\GetOrderByUUID\GetOrderByUUID;
use App\UseCase\GetOrderByUUID\GetOrderByUUIDRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

class GetOrderByUUIDTest extends TestCase
{
    private string $tempPath;

    private GetOrderByUUID $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tempPath = sys_get_temp_dir().'/'.uniqid().'.json';
        copy(__DIR__.'/../../Mocks/orders.json', $this->tempPath);

        $this->useCase = new class extends GetOrderByUUID {
            public string $mockPath;

            protected function getOrdersPath(): string
            {
                return $this->mockPath;
            }
        };
        $this->useCase->mockPath = $this->tempPath;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        if (file_exists($this->tempPath)) {
            unlink($this->tempPath);
        }
    }

    public function testGivenCorrectUUIDShouldReturnOrder(): void
    {
        $product = new Product(new Uuid('2a90c5d1-efee-449c-8134-2b3968bd0de8'), 'Casco Asic', 1099, 'Casco ciclista de Asics', 'cyclism');

        $shoppingCartItem = new ShoppingCartItem($product, 2);
        $order = new Order(new Uuid('2a90c5d1-efee-449c-8134-2b3968bd0de8'), 2198, new Uuid('45659fae-eb17-4c11-b980-988b77c511bc'), new ShoppingCart(new UuidV4('2a90c5d1-efee-449c-8134-2b3968bd0de8'), [$shoppingCartItem]), 'CREATED');
        $actual = $this->useCase->execute(new GetOrderByUUIDRequest(new Uuid('a5d6b55a-663f-4c30-99e3-93cf0d04a29d')));

        $this->assertEquals($order->toArray(), $actual->order->toArray());
    }
}
