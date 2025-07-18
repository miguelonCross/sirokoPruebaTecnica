<?php

declare(strict_types=1);

namespace App\UseCase\GetShoppingCartByClientUUID;

use App\Entity\ShoppingCart;
use App\Entity\ShoppingCartItem;
use Symfony\Component\Uid\UuidV4;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class GetShoppingCartByClientUUID
{
    public function __construct(
        private CacheInterface $cache,
    ) {
    }

    public function execute(GetShoppingCartByClientUUIDRequest $request): GetShoppingCartByClientUUIDResponse
    {
        $cart = $this->cache->get($request->clientUUID->toRfc4122(), function (ItemInterface $item) {
            /**
             * @var ShoppingCartItem[] $shoppingCartItems
             */
            $shoppingCartItems = [];
            $item->expiresAfter(3600);

            return new ShoppingCart(new UuidV4(), $shoppingCartItems);
        });

        return new GetShoppingCartByClientUUIDResponse($cart);
    }
}
