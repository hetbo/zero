<?php

namespace Hetbo\Zero\DTOs;

use InvalidArgumentException;

class CreateCarrotData
{
    public function __construct(
        public readonly string $name,
        public readonly int $length
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? throw new InvalidArgumentException('Name is required'),
            length: (int) ($data['length'] ?? throw new InvalidArgumentException('Length is required'))
        );
    }
}
