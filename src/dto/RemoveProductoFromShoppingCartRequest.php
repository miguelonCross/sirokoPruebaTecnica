<?php

namespace App\dto;

class RemoveProductoFromShoppingCartRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public readonly string $client_uuid,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public readonly string $code,
    )
    {
    }
}
