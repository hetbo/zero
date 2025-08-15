<?php

namespace Hetbo\Zero\Repositories;

use Dotenv\Repository\RepositoryInterface;
use Hetbo\Zero\Models\Carrot;
use Hetbo\Zero\Repositories\Contracts\CarrotRepositoryInterface;

class CarrotRepository implements CarrotRepositoryInterface {
    public function findById(int $id): ?Carrot
    {
        return Carrot::find($id);
    }

    public function create(array $data): Carrot
    {
        return Carrot::create($data);
    }
}