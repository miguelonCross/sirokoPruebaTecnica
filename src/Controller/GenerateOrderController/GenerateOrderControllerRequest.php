<?php

declare(strict_types=1);

namespace App\Controller\GenerateOrderController;

use Symfony\Component\Validator\Constraints as Assert;

class GenerateOrderControllerRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public readonly string $client_uuid,
    ) {
    }
}
