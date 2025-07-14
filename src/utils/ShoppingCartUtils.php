<?php

namespace App\utils;

use App\Entity\ShoppingCartProduct;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ShoppingCartUtils
{
    public static function addOrUpdateItem(string $clientUUID, ShoppingCartProduct $item, CacheItemPoolInterface $cacheItemPool): void
    {
        $isProductInCartAlready = false;
        $cacheItem = $cacheItemPool->getItem($clientUUID);
        $cart = $cacheItem->isHit() ? $cacheItem->get() : [];
        var_dump($cart);
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
    }

    public static function deleteCart(string $clientUUID, CacheInterface $cache): void
    {
        $cache->delete($clientUUID);
    }

    public static function getCreateCart(string $clientUUID, CacheInterface $cache): void
    {
        $cache->get($clientUUID, function (ItemInterface $item) {
            $item->expiresAfter(3600);
            return [];
        });
    }

    public static function deleteItem(string $clientUUID, ShoppingCartProduct $item, CacheItemPoolInterface $cacheItemPool): void
    {
        $cacheItem = $cacheItemPool->getItem($clientUUID);

        $cart = $cacheItem->isHit() ? $cacheItem->get() : [];
        $cart[] = $item->toArray();

        $cacheItem->set($cart);
        $cacheItemPool->save($cacheItem);
    }
}
