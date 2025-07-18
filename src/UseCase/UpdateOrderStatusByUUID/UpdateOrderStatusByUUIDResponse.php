<?php

declare(strict_types=1);

namespace App\UseCase\UpdateOrderStatusByUUID;

use App\Entity\Order;

class UpdateOrderStatusByUUIDResponse
{
    public function __construct(
        public readonly ?Order $order = null,
    ) {
    }
}
