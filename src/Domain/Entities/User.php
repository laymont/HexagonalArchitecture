<?php
namespace Laymont\HexagonalArchitecture\Domain\Entities;

class User
{
    public function __construct(
        public ?int $id,
        public string $name,
        public string $email,
        public string $password,
        public readonly array $extra = [] // Campos adicionales
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            ...$this->extra,
        ];
    }
}
