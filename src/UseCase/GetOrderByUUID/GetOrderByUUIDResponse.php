<?php

declare(strict_types=1);

namespace App\UseCase\GetOrderByUUID;

use App\Entity\Order;

class GetOrderByUUIDResponse
{
    public function __construct(
        public readonly ?Order $order,
    ) {
    }
}
