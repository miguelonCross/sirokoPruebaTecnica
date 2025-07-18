<?php

declare(strict_types=1);

namespace App\UseCase\GetShoppingCartByClientUUID;

use App\Entity\ShoppingCart;
use App\utils\ShoppingCartUtils;
use Symfony\Component\Uid\UuidV4;
use Symfony\Contracts\Cache\CacheInterface;

class GetShoppingCartByClientUUID
{
    public function __construct(
        private CacheInterface $cache,
    ) {
    }

    public function execute(GetShoppingCartByClientUUIDRequest $request): GetShoppingCartByClientUUIDResponse
    {
        $shoppingCart = ShoppingCartUtils::getCart($request->clientUUID, $this->cache);

        return new GetShoppingCartByClientUUIDResponse(new ShoppingCart(new UuidV4($shoppingCart['uuid']), $shoppingCart['products']));
    }
}
