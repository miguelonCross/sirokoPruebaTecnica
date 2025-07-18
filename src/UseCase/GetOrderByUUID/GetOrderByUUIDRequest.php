<?php

declare(strict_types=1);

namespace App\UseCase\GetOrderByUUID;

use Symfony\Component\Uid\Uuid;

class GetOrderByUUIDRequest
{
    public function __construct(
        public readonly Uuid $orderUUID,
    ) {
    }
}
