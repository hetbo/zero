<?php

namespace Hetbo\Zero\Contracts;

use Hetbo\Zero\Models\Carrot;

interface CarrotRepositoryInterface
{
    public function create(array $data): Carrot;
    public function find(int $id): ?Carrot;
    public function findByName(string $name): ?Carrot;
}