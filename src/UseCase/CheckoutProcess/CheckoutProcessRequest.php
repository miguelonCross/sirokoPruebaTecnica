<?php

declare(strict_types=1);

namespace App\UseCase\CheckoutProcess;

use Symfony\Component\Uid\Uuid;

class CheckoutProcessRequest
{
    public function __construct(
        public readonly Uuid $orderUUID,
        public readonly string $pan,
        public readonly string $holder,
        public readonly string $address,
    ) {
    }
}
