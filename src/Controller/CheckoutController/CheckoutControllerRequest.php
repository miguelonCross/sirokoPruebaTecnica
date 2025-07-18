<?php

declare(strict_types=1);

namespace App\Controller\CheckoutController;

use Symfony\Component\Validator\Constraints as Assert;

class CheckoutControllerRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public readonly string $order_uuid,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public readonly string $pan,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public readonly string $holder,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public readonly string $address,
    ) {
    }
}
