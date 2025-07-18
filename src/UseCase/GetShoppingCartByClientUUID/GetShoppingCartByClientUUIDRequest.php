<?php

declare(strict_types=1);

namespace App\UseCase\GetShoppingCartByClientUUID;

use Symfony\Component\Uid\Uuid;

class GetShoppingCartByClientUUIDRequest
{
    public function __construct(
        public readonly Uuid $clientUUID,
    ) {
    }
}
