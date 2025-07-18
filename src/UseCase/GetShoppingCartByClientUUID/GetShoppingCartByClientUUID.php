<?php

declare(strict_types=1);

namespace App\UseCase\GetShoppingCartByClientUUID;

use App\Entity\Product;
use App\Entity\ShoppingCart;
use App\Entity\ShoppingCartItem;
use App\utils\ShoppingCartUtils;
use Symfony\Component\Uid\Uuid;
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
        $shoppingCart = ShoppingCartUtils::getCart($request->clientUUID, $this->cache);

        /*
         * @var ShoppingCart $cart
         */
        /*$cart = $this->cache->get($request->clientUUID->toRfc4122(), function (ItemInterface $item) {
            $item->expiresAfter(3600);

            return [
                'uuid' => Uuid::v4()->toRfc4122(),
                'products' => [],
            ];
        });

        $items = [];
        foreach ($cart['products'] as $shoppingCartItem) {
            $product = Product::toEntity($shoppingCartItem);
            $items[] = new ShoppingCartItem($product, $shoppingCartItem['quantity']);
        }

        return new GetShoppingCartByClientUUIDResponse(new ShoppingCart($cart['uuid'], $items));*/
        return new GetShoppingCartByClientUUIDResponse(new ShoppingCart(new UuidV4($shoppingCart['uuid']), $shoppingCart['items']));
    }
}
