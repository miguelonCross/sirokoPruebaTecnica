<?php

declare(strict_types=1);

namespace App\dto;

use App\Entity\Order;

class OrderDTO
{
    public function __construct(
        public readonly Order $order,
    ) {
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return [
            'order' => $this->order->toArray(),
        ];
    }
}
