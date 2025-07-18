<?php

declare(strict_types=1);

namespace App\UseCase\GenerateOrderByClientUUID;

use Symfony\Component\Uid\Uuid;

class GenerateOrderByClientUUIDRequest
{
    public function __construct(
        public readonly Uuid $clientUUID,
    ) {
    }
}
