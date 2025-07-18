<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Uid\Uuid;

class PaymentResponse
{
    public function __construct(
        public readonly Uuid $paymentUUID,
        public readonly string $paymentCode,
        public readonly string $status,
    ) {
    }
}
