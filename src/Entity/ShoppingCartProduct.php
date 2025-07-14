<?php

namespace App\Entity;

class ShoppingCartProduct
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type('string')]
        private string $code,

        #[Assert\NotBlank]
        #[Assert\Type('integer')]
        private int $quantity,

        #[Assert\NotBlank]
        #[Assert\Type('integer')]
        private int $price,
    )
    {
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getPricePerUnit(): int
    {
        return $this->price;
    }

    public function totalPrice(): int
    {
        return $this->price * $this->quantity;
    }

    public function toArray(): array
    {
        return [
            'code' => $this->getCode(),
            'quantity' => $this->getQuantity(),
            'price' => $this->getPricePerUnit(),
            'totalPrice' => $this->totalPrice(),
        ];
    }
}
