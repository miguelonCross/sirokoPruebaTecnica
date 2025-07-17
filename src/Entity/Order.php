<?php

namespace App\Entity;

use Symfony\Component\Uid\Uuid;

class Order
{
    /**
     * @param ShoppingCartItem[] $shoppingCart
     */
    public function __construct(
        private Uuid $uuid,
        private int $amount,
        private Uuid $clientUUID,
        private array $shoppingCart,
    )
    {
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getClientUUID(): Uuid
    {
        return $this->clientUUID;
    }

    /**
     * @return ShoppingCartItem[]
     */
    public function getShoppingCartItem(): array
    {
        return $this->shoppingCart;
    }

    public function toArray(): array
    {
        $items = [];
        foreach ($this->getShoppingCartItem() as $item) {
            $items[] = $item->toArray();
        }

        return [
            'uuid' => $this->getUuid(),
            'amount' => $this->getAmount(),
            'clientUUID' => $this->getClientUUID(),
            'items' => $items,
        ];
    }

}
