<?php

declare(strict_types=1);

namespace App\UseCase\UpdateOrderStatusByUUID;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\ShoppingCart;
use App\Entity\ShoppingCartItem;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

class UpdateOrderStatusByUUID
{
    public function execute(UpdateOrderStatusByUUIDRequest $request): UpdateOrderStatusByUUIDResponse
    {
        $ordersFile = file_get_contents(__DIR__.'/../../mocks/orders.json');
        $data = json_decode((string) $ordersFile, true, JSON_THROW_ON_ERROR);

        $orders = [];
        $updatedOrder = null;

        for ($i = 0; $i < count($data); ++$i) {
            $actualOrder = $data[$i];
            if ($actualOrder['uuid'] === $request->orderUUID->toRfc4122()) {
                $shoppingCart = $actualOrder['shopping_cart'];
                $items = $shoppingCart['items'];

                $products = [];
                foreach ($items as $item) {
                    $products[] = ShoppingCartItem::toEntity(
                        new Product(
                            new Uuid($item['uuid']),
                            $item['name'],
                            $item['price'],
                            $item['description'],
                            $item['category']
                        ),
                        $item['quantity']
                    );
                }

                $updatedOrder = new Order(
                    $request->orderUUID,
                    $actualOrder['amount'],
                    new Uuid($actualOrder['client_uuid']),
                    new ShoppingCart(new UuidV4($shoppingCart['uuid']), $products),
                    $request->status,
                );
                $orders[] = $updatedOrder->toArray();
            } else {
                $orders[] = $actualOrder;
            }
        }

        file_put_contents(__DIR__.'/../../mocks/orders.json', (string) json_encode($orders), LOCK_EX);

        return new UpdateOrderStatusByUUIDResponse($updatedOrder);
    }
}
