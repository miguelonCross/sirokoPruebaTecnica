<?php

declare(strict_types=1);

namespace App\UseCase\GetOrderByUUID;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\ShoppingCart;
use App\Entity\ShoppingCartItem;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

class GetOrderByUUID
{
    public function execute(GetOrderByUUIDRequest $request): GetOrderByUUIDResponse
    {
        $ordersFile = $this->loadJson();
        $data = json_decode((string) $ordersFile, true, JSON_THROW_ON_ERROR);
        $order = null;

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

                $order = new Order(
                    $request->orderUUID,
                    $actualOrder['amount'],
                    new Uuid($actualOrder['client_uuid']),
                    new ShoppingCart(new UuidV4($shoppingCart['uuid']), $products),
                    $actualOrder['status'],
                );
            }
        }

        return new GetOrderByUUIDResponse($order);
    }

    protected function loadJson(): string
    {
        return (string) file_get_contents(__DIR__.'/../../mocks/orders.json');
    }
}
