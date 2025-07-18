<?php

declare(strict_types=1);

namespace App\UseCase\CheckoutProcess;

use App\Entity\Order;

class CheckoutProcessResponse
{
    public function __construct(
        public readonly ?Order $order,
    ) {
    }
}
