<?php

namespace App\dto;

use Symfony\Component\Validator\Constraints as Assert;


class ShoppingCartRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public readonly string $client_uuid
    )
    {
    }
}
