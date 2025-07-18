<?php

declare(strict_types=1);

namespace App\UseCase\GetProductByUUID;

use App\Entity\Product;

class GetProductByUUIDResponse
{
    public function __construct(
        public readonly ?Product $product,
    ) {
    }
}
