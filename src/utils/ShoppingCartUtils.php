<?php

declare(strict_types=1);

namespace App\utils;

use App\Entity\ShoppingCart;
use App\Entity\ShoppingCartItem;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Cache\CacheInterface;

class ShoppingCartUtils
{
    public function __construct(
    ) {
    }

    public static function addOrUpdateItem(Uuid $clientUUID, ShoppingCartItem $item, CacheItemPoolInterface $cacheItemPool): ShoppingCart
    {
        $isProductInCartAlready = false;

        $cacheItem = $cacheItemPool->getItem($clientUUID->toRfc4122());

        /**
         * @var ShoppingCart $cart
         */
        $cart = $cacheItem->isHit() ? $cacheItem->get() : ['uuid' => Uuid::v4()->toRfc4122(), 'items' => []];

        /**
         * @var ShoppingCartItem[] $productsInCart
         */
        $productsInCart = $cart->shoppingCartItems;

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
            var_dump($item->quantity);
            $productsInCart[] = $item;
        }
        $updatedCart = new ShoppingCart($cart->uuid, $productsInCart);

        $cacheItem->set($updatedCart);
        $cacheItemPool->save($cacheItem);

        return $updatedCart;
    }

    public static function deleteCart(Uuid $clientUUID, CacheInterface $cache): void
    {
        $cache->delete($clientUUID->toRfc4122());
    }

    public static function deleteItem(Uuid $clientUUID, string $productUUID, CacheItemPoolInterface $cacheItemPool): ?bool
    {
        $cacheItem = $cacheItemPool->getItem($clientUUID->toRfc4122());
        $isDeletedItem = false;

        if ($cacheItem->isHit()) {
            /**
             * @var ShoppingCart $cart
             */
            $cart = $cacheItem->get();

            /**
             * @var ShoppingCartItem[] $productsInCart
             */
            $productsInCart = $cart->shoppingCartItems;

            $remainingProducts = [];
            for ($i = 0; $i < count($productsInCart); ++$i) {
                if ($productsInCart[$i]->product->uuid->toRfc4122() === $productUUID) {
                    $isDeletedItem = true;
                } else {
                    $remainingProducts[] = $productsInCart[$i];
                }
            }

            $updatedCart = new ShoppingCart($cart->uuid, $remainingProducts);
            $cacheItem->set($updatedCart);
            $cacheItemPool->save($cacheItem);

            return $isDeletedItem;
        }

        return null;
    }
}
