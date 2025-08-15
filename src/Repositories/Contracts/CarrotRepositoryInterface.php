<?php

namespace Hetbo\Zero\Repositories\Contracts;

use Hetbo\Zero\Models\Carrot;

interface CarrotRepositoryInterface {

    public function findById(int $id): ?Carrot;
    public function create(array $data): Carrot;

}