<?php

declare(strict_types=1);

namespace App\UseCase\GenerateOrderByClientUUID;

use App\Entity\Order;
use App\Entity\ShoppingCart;
use App\UseCase\GetShoppingCartByClientUUID\GetShoppingCartByClientUUID;
use App\UseCase\GetShoppingCartByClientUUID\GetShoppingCartByClientUUIDRequest;
use App\utils\ShoppingCartUtils;
use Symfony\Component\Uid\UuidV4;
use Symfony\Contracts\Cache\CacheInterface;

class GenerateOrderByClientUUID
{
    public function __construct(
        private GetShoppingCartByClientUUID $getShoppingCartByClientUUID,
        private CacheInterface $cache,
    ) {
    }

    public function execute(GenerateOrderByClientUUIDRequest $request): GenerateOrderByClientUUIDResponse
    {
        $order = null;
        $shoppingCart = $this->getShoppingCartByClientUUID->execute(new GetShoppingCartByClientUUIDRequest($request->clientUUID))->shoppingCart;

        if (!empty($shoppingCart->shoppingCartItems)) {
            $amount = $this->calculateTotalAmount($shoppingCart);

            $order = new Order(UuidV4::v4(), $amount, $request->clientUUID, $shoppingCart, 'CREATED');

            $ordersFile = file_get_contents(__DIR__.'/../../mocks/orders.json');
            $orders = json_decode((string) $ordersFile, true);

            $orders[] = $order->toArray();

            $isReaded = file_put_contents(__DIR__.'/../../mocks/orders.json', (string) json_encode($orders), LOCK_EX);
            if (false === $isReaded) {
                throw new \Exception('Unable to write orders.json');
            }

            ShoppingCartUtils::deleteCart($request->clientUUID, $this->cache);
        }

        return new GenerateOrderByClientUUIDResponse($order);
    }

    private function calculateTotalAmount(ShoppingCart $shoppingCart): int
    {
        $items = $shoppingCart->shoppingCartItems;
        $amount = 0;

        foreach ($items as $item) {
            $product = $item->product;
            $quantity = $item->quantity;

            $amount += $quantity * $product->price;
        }

        return $amount;
    }
}
