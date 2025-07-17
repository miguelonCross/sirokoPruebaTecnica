<?php

namespace App\Entity;

use Symfony\Component\Uid\Uuid;

class Product
{
    public function __construct(
        private Uuid $uuid,
        private string $name,
        private int $price,
        private string $description,
        private string $category
    )
    {
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function toArray(): array
    {
        return [
            'uuid'  => $this->getUuid()->toRfc4122(),
            'name'  => $this->getName(),
            'price' => $this->getPrice(),
            'description' => $this->getDescription(),
            'category' => $this->getCategory(),
        ];
    }
}
