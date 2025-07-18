<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Uid\Uuid;

class Product
{
    public function __construct(
        public readonly Uuid $uuid,
        public readonly string $name,
        public readonly int $price,
        public readonly string $description,
        public readonly string $category,
    ) {
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid->toRfc4122(),
            'name' => $this->name,
            'price' => $this->price,
            'description' => $this->description,
            'category' => $this->category,
        ];
    }
}
