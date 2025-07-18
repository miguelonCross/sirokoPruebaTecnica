<?php

declare(strict_types=1);

namespace App\Tests\UseCases\GetProductByUUIDTest;

use App\Entity\Product;
use App\UseCase\GetProductByUUID\GetProductByUUID;
use App\UseCase\GetProductByUUID\GetProductByUUIDRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class GetProductByUUIDTest extends TestCase
{
    private GetProductByUUID $useCase;

    protected function setUp(): void
    {
        $this->useCase = new GetProductByUUID();
    }

    public function testGivenCorrectProductUUIDShouldReturn(): void
    {
        $productUUID = new Uuid('2a90c5d1-efee-449c-8134-2b3968bd0de8');

        $product = new Product(new Uuid('2a90c5d1-efee-449c-8134-2b3968bd0de8'), 'Casco Asic', 1099, 'Casco ciclista de Asics', 'cyclism');

        $response = $this->useCase->execute(new GetProductByUUIDRequest($productUUID));
        $this->assertSame($product->toArray(), $response->product->toArray());
    }

    public function testGivenWrongProductUUIDShouldReturnNull(): void
    {
        $productUUID = new Uuid('2a90c5d1-efee-449c-8134-2b3968bd0da2');

        $response = $this->useCase->execute(new GetProductByUUIDRequest($productUUID));
        $this->assertSame(null, $response->product);
    }
}
