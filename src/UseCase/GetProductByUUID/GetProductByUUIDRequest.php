<?php

declare(strict_types=1);

namespace App\UseCase\GetProductByUUID;

use Symfony\Component\Uid\Uuid;

class GetProductByUUIDRequest
{
    public function __construct(
        public readonly Uuid $productUUID,
    ) {
    }
}
