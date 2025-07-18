<?php

declare(strict_types=1);

namespace App\Controller\ShoppingCartController;

use Symfony\Component\Validator\Constraints as Assert;

class ShoppingCartControllerRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public readonly string $client_uuid,
    ) {
    }
}
