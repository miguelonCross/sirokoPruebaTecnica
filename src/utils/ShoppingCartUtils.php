<?php

namespace App\utils;

use App\Entity\ShoppingCart;
use App\Entity\ShoppingCartProduct;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ShoppingCartUtils
{
    public static function addOrUpdateItem(string $clientUUID, ShoppingCartProduct $item, CacheItemPoolInterface $cacheItemPool): ShoppingCart
    {
        $isProductInCartAlready = false;
        $cacheItem = $cacheItemPool->getItem($clientUUID);
        $cart = $cacheItem->isHit() ? $cacheItem->get() : [];
       for ($i = 0; $i < count($cart); $i++) {
               if ($cart[$i]['code'] === $item->getCode()){
                   if ($item->getQuantity() === 0){
                   unset($cart[$i]);
               } else {
                   $cart[$i] = $item->toArray();
               }
               $isProductInCartAlready = true;
           }
       }
       if (!$isProductInCartAlready && $item->getQuantity() > 0){
           $cart[] = $item->toArray();
       }

        $cacheItem->set($cart);
        $cacheItemPool->save($cacheItem);

        return ShoppingCart::toEntity($cart);
    }

    public static function deleteCart(string $clientUUID, CacheInterface $cache): void
    {
        $cache->delete($clientUUID);
    }

    public static function getCart(string $clientUUID, CacheInterface $cache): ShoppingCart
    {
        $cart = $cache->get($clientUUID, function (ItemInterface $item) {
            $item->expiresAfter(3600);
            return [];
        });

        return ShoppingCart::toEntity($cart);
    }

    public static function deleteItem(string $clientUUID, ShoppingCartProduct $item, CacheItemPoolInterface $cacheItemPool): ShoppingCart
    {
        $cacheItem = $cacheItemPool->getItem($clientUUID);
        $cart = $cacheItem->isHit() ? $cacheItem->get() : [];
        return ShoppingCart::toEntity($cart);
    }

    public static function deleteProduct(string $clientUUID, string $productCode, CacheItemPoolInterface $cacheItemPool): bool | null
    {
        $cacheItem = $cacheItemPool->getItem($clientUUID);

        if($cacheItem->isHit()){
            $cart = $cacheItem->get();
            for ($i = 0; $i < count($cart); $i++) {
                if ($cart[$i]['code'] === $productCode){
                    unset($cart[$i]);
                    return true;
                }
            }
            return false;
        }
        return null;
    }
}
