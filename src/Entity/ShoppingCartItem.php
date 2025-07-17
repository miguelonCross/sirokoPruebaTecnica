<?php

namespace App\Entity;

class ShoppingCartItem
{
    public function __construct(
        private ?Product $product = null,
        private ?int $quantity = null,
    )
    {
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function toArray(): array
    {
        $product = $this->getProduct()->toArray() ?? [];
        if (!empty($product)) {
            $product['quantity'] = $this->getQuantity();
        }

        return $product;
    }

    public function toEntity(Product $product, int $quantity): ShoppingCartItem
    {
        return new ShoppingCartItem($product, $quantity);
    }
}
