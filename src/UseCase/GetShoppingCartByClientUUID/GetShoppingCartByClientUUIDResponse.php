<?php

declare(strict_types=1);

namespace App\UseCase\GetShoppingCartByClientUUID;

use App\Entity\ShoppingCart;

class GetShoppingCartByClientUUIDResponse
{
    public function __construct(
        public readonly ShoppingCart $shoppingCart,
    ) {
    }
}
