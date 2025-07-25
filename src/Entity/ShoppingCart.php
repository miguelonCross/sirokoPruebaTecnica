<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Uid\UuidV4;

class ShoppingCart
{
    /**
     * @param ShoppingCartItem[] $shoppingCartItems
     */
    public function __construct(
        public readonly UuidV4 $uuid,
        public readonly array $shoppingCartItems,
    ) {
    }
}
