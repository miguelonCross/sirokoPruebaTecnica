<?php

namespace App\Controller\DeleteShoppingCartController;

use Symfony\Component\Validator\Constraints as Assert;

class DeleteShoppingCartControllerRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public readonly string $client_uuid,
    )
    {
    }
}
