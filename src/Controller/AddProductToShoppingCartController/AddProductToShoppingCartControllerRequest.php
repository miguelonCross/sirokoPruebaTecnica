<?php

namespace App\Controller\AddProductToShoppingCartController;

use Symfony\Component\Validator\Constraints as Assert;

class AddProductToShoppingCartControllerRequest {

    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public readonly string $client_uuid,

        #[Assert\NotBlank]
        #[Assert\Uuid]
        public readonly string $product_uuid,

        #[Assert\NotBlank]
        #[Assert\GreaterThan(0)]
        #[Assert\Type('integer')]
        public readonly int $quantity,
    )
    {
    }
}
