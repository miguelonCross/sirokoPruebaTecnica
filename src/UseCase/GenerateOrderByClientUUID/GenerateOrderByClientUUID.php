<?php

declare(strict_types=1);

namespace App\UseCase\GenerateOrderByClientUUID;

use App\Entity\Order;
use App\Entity\ShoppingCart;
use App\UseCase\GetShoppingCartByClientUUID\GetShoppingCartByClientUUID;
use App\UseCase\GetShoppingCartByClientUUID\GetShoppingCartByClientUUIDRequest;
use Symfony\Component\Uid\UuidV4;

class GenerateOrderByClientUUID
{
    public function __construct(
        private GetShoppingCartByClientUUID $getShoppingCartByClientUUID,
    ) {
    }

    public function execute(GenerateOrderByClientUUIDRequest $request): GenerateOrderByClientUUIDResponse
    {
        $order = null;
        $shoppingCart = $this->getShoppingCartByClientUUID->execute(new GetShoppingCartByClientUUIDRequest($request->clientUUID))->shoppingCart;

        if (!empty($shoppingCart->shoppingCartItem)) {
            $amount = $this->calculateTotalAmount($shoppingCart);

            $order = new Order(UuidV4::v4(), $amount, $request->clientUUID, $shoppingCart, 'CREATED');

            $ordersFile = file_get_contents(__DIR__.'/../../mocks/orders.json');
            $orders = json_decode((string) $ordersFile, true);

            $orders[$order->uuid->toRfc4122()] = $order->toArray();

            $isReaded = file_put_contents((string) $ordersFile, (string) json_encode($orders), LOCK_EX);
            if (false === $isReaded) {
                throw new \Exception('Unable to write orders.json');
            }

            var_dump('ORDERS');
            var_dump($orders);
            var_dump('ARCHIVO');
            var_dump(file_get_contents(__DIR__.'/../../mocks/orders.json'));
        }

        return new GenerateOrderByClientUUIDResponse($order);
    }

    private function calculateTotalAmount(ShoppingCart $shoppingCart): int
    {
        $items = $shoppingCart->shoppingCartItem;
        $amount = 0;

        foreach ($items as $item) {
            $product = $item->product;
            $quantity = $item->quantity;

            $amount += $quantity * $product->price;
        }

        return $amount;
    }
}
