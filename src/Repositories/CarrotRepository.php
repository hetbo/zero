<?php

namespace Hetbo\Zero\Repositories;

use Hetbo\Zero\Contracts\CarrotRepositoryInterface;
use Hetbo\Zero\Models\Carrot;

class CarrotRepository implements CarrotRepositoryInterface
{
    public function create(array $data): Carrot
    {
        return Carrot::create($data);
    }

    public function find(int $id): ?Carrot
    {
        return Carrot::find($id);
    }

    public function findByName(string $name): ?Carrot
    {
        return Carrot::where('name', $name)->first();
    }
}