<?php

declare(strict_types=1);

namespace App\dto;

use App\Entity\ShoppingCartItem;

class ShoppingCartDTO
{
    /**
     * @param ShoppingCartItem[] $shoppingCartItems
     */
    public function __construct(
        public readonly string $shoppingCartUuid,
        public readonly array $shoppingCartItems,
    ) {
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        $products = [];
        foreach ($this->shoppingCartItems as $shoppingCartItem) {
            $products[] = $shoppingCartItem->toArray();
        }

        return [
            'uuid' => $this->shoppingCartUuid,
            'items' => $products,
        ];
    }
}
