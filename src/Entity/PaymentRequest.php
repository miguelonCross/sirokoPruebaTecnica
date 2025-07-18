<?php

declare(strict_types=1);

namespace App\Entity;

class PaymentRequest
{
    /**
     * @param array<mixed> $metadata
     */
    public function __construct(
        public readonly string $pan,
        public readonly int $amount,
        public readonly string $holder,
        public readonly string $operative,
        public readonly string $paymentMethod,
        public readonly array $metadata,
    ) {
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return [
            'pan' => $this->pan,
            'amount' => $this->amount,
            'holder' => $this->holder,
            'operative' => $this->operative,
            'payment_method' => $this->paymentMethod,
            'metadata' => $this->metadata,
        ];
    }
}
