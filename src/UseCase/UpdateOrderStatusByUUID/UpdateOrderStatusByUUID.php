<?php

declare(strict_types=1);

namespace App\UseCase\UpdateOrderStatusByUUID;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\ShoppingCart;
use App\Entity\ShoppingCartItem;
use Symfony\Component\Uid\Uuid;

class UpdateOrderStatusByUUID
{
    public function execute(UpdateOrderStatusByUUIDRequest $request): UpdateOrderStatusByUUIDResponse
    {
        $ordersFile = file_get_contents(__DIR__.'/../../mocks/orders.json');
        $orders = json_decode((string) $ordersFile, true);

        foreach ($orders as $key => $value) {
            if ($key === $request->orderUUID->toRfc4122()) {
                $shoppingCart = $value['shopping_cart'];
                $items = $value['shopping_cart']['items'];
                $products = [];
                foreach ($items as $item) {
                    $products[] = ShoppingCartItem::toEntity(
                        new Product(
                            $item['uuid'],
                            $item['name'],
                            $item['price'],
                            $item['description'],
                            $item['category']
                        ),
                        $item['quantity']
                    );
                }

                return new UpdateOrderStatusByUUIDResponse(
                    new Order(
                        $request->orderUUID,
                        $value['amount'],
                        new Uuid($value['client_uuid']),
                        new ShoppingCart($shoppingCart['uuid'], $products),
                        $value['products'],
                    )
                );
            }
        }

        return new UpdateOrderStatusByUUIDResponse(null);
    }
}
