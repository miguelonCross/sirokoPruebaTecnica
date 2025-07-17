<?php

namespace App\Entity;

use Symfony\Component\Uid\Uuid;

class ShoppingCart
{
    /**
     * @param ShoppingCartItem[] $shoppingCartItem
     */
    public function __construct(
        private Uuid $uuid,
        private array $shoppingCartItem,
    )
    {
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    /**
     * @return ShoppingCartItem[]
     */
    public function getShoppingCartItem(): array
    {
        return $this->shoppingCartItem;
    }
}
