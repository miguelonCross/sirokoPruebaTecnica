<?php

namespace App\Entity;

use Symfony\Component\Uid\Uuid;

class Order
{
    public function __construct(
        private Uuid $uuid,
        private int $amount,
        private Uuid $clientUUID,
        private ShoppingCart $shoppingCart,
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

    public function getShoppingCart(): ShoppingCart
    {
        return $this->shoppingCart;
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->getUuid(),
            'amount' => $this->getAmount(),
            'clientUUID' => $this->getClientUUID(),
            'shoppingCart' => $this->getShoppingCart()->toArray(),
        ];
    }

}
