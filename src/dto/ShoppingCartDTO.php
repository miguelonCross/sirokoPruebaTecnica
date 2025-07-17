<?php

namespace App\dto;

use App\Entity\ShoppingCartItem;

class ShoppingCartDTO
{
    /**
     * @param ShoppingCartItem[] $shoppingCartItems
     */
    public function __construct(
        private string $shoppingCartUuid,
        private array $shoppingCartItems,
    )
    {
    }

    public function getShoppingCartUuid(): string
    {
        return $this->shoppingCartUuid;
    }

    /**
     * @return ShoppingCartItem[]
     */
    public function getShoppingCartItems(): array
    {
        return $this->shoppingCartItems;
    }

    public function toArray(): array
    {
        $products = [];
        foreach ($this->getShoppingCartItems() as $shoppingCartItem) {
            $products[] = $shoppingCartItem->toArray();
        }
        return [
            'uuid' => $this->getShoppingCartUuid(),
            'products' => $products,
        ];
    }
}
