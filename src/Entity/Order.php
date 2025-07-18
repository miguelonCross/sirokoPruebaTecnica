<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Uid\Uuid;

class Order
{
    public function __construct(
        public readonly Uuid $uuid,
        public readonly int $amount,
        public readonly Uuid $clientUUID,
        public readonly ShoppingCart $shoppingCart,
        public readonly string $status,
    ) {
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        $items = [];
        foreach ($this->shoppingCart->shoppingCartItems as $item) {
            $items[] = $item->toArray();
        }

        return [
            'uuid' => $this->uuid->toRfc4122(),
            'amount' => $this->amount,
            'client_uuid' => $this->clientUUID->toRfc4122(),
            'shopping_cart' => [
                'uuid' => $this->shoppingCart->uuid,
                'items' => $items,
            ],
            'status' => $this->status,
        ];
    }
}
