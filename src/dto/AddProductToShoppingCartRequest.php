<?php

namespace App\dto;

use App\Entity\ShoppingCartProduct;
use Symfony\Component\Validator\Constraints as Assert;

class AddProductToShoppingCartRequest {

    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public readonly string $client_uuid,

        public readonly ShoppingCartProduct $product,
    )
    {
    }
}
