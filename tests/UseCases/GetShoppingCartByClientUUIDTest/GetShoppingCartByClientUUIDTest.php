<?php

declare(strict_types=1);

namespace App\Tests\UseCases\GetShoppingCartByClientUUIDTest;

use App\Entity\ShoppingCart;
use App\UseCase\GetShoppingCartByClientUUID\GetShoppingCartByClientUUID;
use App\UseCase\GetShoppingCartByClientUUID\GetShoppingCartByClientUUIDRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class GetShoppingCartByClientUUIDTest extends TestCase
{
    public function testReturnsCachedCartOnSecondCall(): void
    {
        $clientUuid = new UuidV4();

        $cache = $this->createMock(CacheInterface::class);

        $expectedCart = new ShoppingCart(new UuidV4(), []);

        $cache
            ->expects(self::once())
            ->method('get')
            ->with($clientUuid->toRfc4122())
            ->willReturnCallback(function (string $key, callable $callback) {
                $item = $this->createMock(ItemInterface::class);
                $item
                    ->expects($this->once())
                    ->method('expiresAfter')
                    ->with(3600);

                return $callback($item);
            });

        $useCase = new GetShoppingCartByClientUUID($cache);
        $request = new GetShoppingCartByClientUUIDRequest($clientUuid);

        $cache
            ->expects(self::once())
            ->method('get')
            ->with($clientUuid->toRfc4122())
            ->willReturn($expectedCart);

        $response = $useCase->execute($request);
        $this->assertSame($expectedCart->shoppingCartItems, $response->shoppingCart->shoppingCartItems);
    }

    public function testItProvidesEmptyCartOnCacheMiss(): void
    {
        $clientUuid = new UuidV4();

        $cache = $this->createMock(CacheInterface::class);

        $useCase = new GetShoppingCartByClientUUID($cache);
        $request = new GetShoppingCartByClientUUIDRequest($clientUuid);

        $cache
            ->method('get')
            ->willReturnCallback(function (string $key, callable $callback) {
                /** @var ItemInterface $item */
                $item = $this->createMock(ItemInterface::class);
                $item
                    ->expects($this->once())
                    ->method('expiresAfter')
                    ->with(3600);

                return $callback($item);
            });

        $response = $useCase->execute($request);

        $cart = $response->shoppingCart;
        $this->assertInstanceOf(ShoppingCart::class, $cart);
        $this->assertEmpty($cart->shoppingCartItems);
    }
}
