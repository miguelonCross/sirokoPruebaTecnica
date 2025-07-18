<?php

declare(strict_types=1);

namespace App\UseCase\GetShoppingCart;

use Symfony\Component\Uid\UuidV4;

class GetShoppingCartRequest
{
    public function __construct(
        public readonly UuidV4 $clientUUID,
    ) {
    }
}
