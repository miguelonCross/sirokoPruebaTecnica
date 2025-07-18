<?php

declare(strict_types=1);

namespace App\UseCase\GenerateOrderByClientUUID;

use App\Entity\Order;

class GenerateOrderByClientUUIDResponse
{
    public function __construct(
        public readonly ?Order $order = null,
    ) {
    }
}
