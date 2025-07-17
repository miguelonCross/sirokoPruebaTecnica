<?php

namespace App\Controller\RemoveProductFromShoppingCartController;

use Symfony\Component\Validator\Constraints as Assert;

class RemoveProductFromShoppingCartControllerRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public readonly string $client_uuid,

        #[Assert\NotBlank]
        #[Assert\Uuid]
        public readonly string $product_uuid,
    )
    {
    }
}
