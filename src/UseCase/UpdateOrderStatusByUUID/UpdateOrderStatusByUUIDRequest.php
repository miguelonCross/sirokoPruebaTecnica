<?php

declare(strict_types=1);

namespace App\UseCase\UpdateOrderStatusByUUID;

use Symfony\Component\Uid\Uuid;

class UpdateOrderStatusByUUIDRequest
{
    public function __construct(
        public readonly Uuid $orderUUID,
        public readonly string $status,
    ) {
    }
}
