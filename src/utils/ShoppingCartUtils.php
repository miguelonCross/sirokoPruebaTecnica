<?php

declare(strict_types=1);

namespace App\utils;

use App\dto\ShoppingCartDTO;
use App\Entity\ShoppingCart;
use App\Entity\ShoppingCartItem;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ShoppingCartUtils
{
    public function __construct(
    ) {
    }

    public static function addOrUpdateItem(Uuid $clientUUID, ShoppingCartItem $item, CacheItemPoolInterface $cacheItemPool): ShoppingCartDTO
    {
        $isProductInCartAlready = false;

        $cacheItem = $cacheItemPool->getItem($clientUUID->toRfc4122());
        $cart = $cacheItem->isHit() ? $cacheItem->get() : ['uuid' => Uuid::v4()->toRfc4122(), 'products' => []];

        /**
         * @var ShoppingCartItem[] $productsInCart
         */
        $productsInCart = $cart['products'];

        for ($i = 0; $i < count($productsInCart); ++$i) {
            if ($productsInCart[$i]->product->uuid == $item->product->uuid->toRfc4122()) {
                if (0 === $item->quantity) {
                    unset($productsInCart[$i]);
                } else {
                    $productsInCart[$i] = $item;
                }
                $isProductInCartAlready = true;
            }
        }

        if (!$isProductInCartAlready && $item->quantity > 0) {
            $productsInCart[] = $item;
        }
        $cart['products'] = $productsInCart;

        $cacheItem->set($cart);
        $cacheItemPool->save($cacheItem);

        return new ShoppingCartDTO($cart['uuid'], $cart['products']);
    }

    public static function deleteCart(Uuid $clientUUID, CacheInterface $cache): void
    {
        $cache->delete($clientUUID->toRfc4122());
    }

    /**
     * @return array<mixed>
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public static function getCart(Uuid $clientUUID, CacheInterface $cache): array
    {
        /**
         * @var ShoppingCart $cart
         */
        $cart = $cache->get($clientUUID->toRfc4122(), function (ItemInterface $item) {
            /**
             * @var ShoppingCartItem[] $shoppingCartItems
             */
            $shoppingCartItems = [];
            $item->expiresAfter(3600);

            return [
                'uuid' => Uuid::v4()->toRfc4122(),
                'products' => $shoppingCartItems,
            ];
        });

        return $cart;
    }

    public static function deleteItem(Uuid $clientUUID, string $productUUID, CacheItemPoolInterface $cacheItemPool): ?bool
    {
        $cacheItem = $cacheItemPool->getItem($clientUUID->toRfc4122());
        $isDeletedItem = false;

        if ($cacheItem->isHit()) {
            $cart = $cacheItem->get();

            /**
             * @var ShoppingCartItem[] $productsInCart
             */
            $productsInCart = $cart['products'];

            $remainingProducts = [];
            for ($i = 0; $i < count($productsInCart); ++$i) {
                if ($productsInCart[$i]->product->uuid->toRfc4122() === $productUUID) {
                    $isDeletedItem = true;
                } else {
                    $remainingProducts[] = $productsInCart[$i];
                }
            }

            $cart['products'] = $remainingProducts;
            $cacheItem->set($cart);
            $cacheItemPool->save($cacheItem);

            return $isDeletedItem;
        }

        return null;
    }
}
