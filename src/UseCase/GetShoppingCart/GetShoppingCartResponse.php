<?php

declare(strict_types=1);

namespace App\UseCase\GetShoppingCart;

use App\Entity\ShoppingCart;

class GetShoppingCartResponse
{
    public function __construct(
        public readonly ShoppingCart $shoppingCart,
    ) {
    }
}
