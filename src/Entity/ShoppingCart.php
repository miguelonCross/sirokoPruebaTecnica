<?php

namespace App\Entity;

class ShoppingCart
{
    /**
     * @param ShoppingCartProduct[] $products
     */
    public function __construct(
        private array $products,
    )
    {
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function toArray(): array
    {
        $data = [];
        foreach ($this->products as $product){
            $data[] = $product->toArray();
        }

        return $data;
    }

    public static function toEntity(array $products): ShoppingCart
    {
        $data = [];
        foreach ($products as $product) {
            $data[] = new ShoppingCartProduct($product['code'], $product['quantity'], $product['price']);
        }
        return new ShoppingCart($data);
    }
}
