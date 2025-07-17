<?php

namespace App\UseCase\GetProductByUUID;

use App\Entity\ShoppingCart;

class GetProductByUUIDResponse
{
    public function __construct(
        public readonly ?ShoppingCart $shoppingCart
    )
    {
    }
}
