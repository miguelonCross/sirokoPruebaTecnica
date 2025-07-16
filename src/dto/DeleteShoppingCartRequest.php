<?php

namespace App\dto;

use Symfony\Component\Validator\Constraints as Assert;

class DeleteShoppingCartRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public readonly string $client_uuid,
    )
    {
    }
}
