<?php

declare(strict_types=1);

namespace App\Entity;

class ShoppingCartItem
{
    public function __construct(
        public readonly ?Product $product = null,
        public readonly ?int $quantity = null,
    ) {
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        $product = $this->product?->toArray() ?? [];
        if (!empty($product)) {
            $product['quantity'] = $this->quantity;
        }

        return $product;
    }

    public static function toEntity(Product $product, int $quantity): ShoppingCartItem
    {
        return new ShoppingCartItem($product, $quantity);
    }
}
