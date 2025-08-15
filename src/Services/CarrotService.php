<?php

namespace Hetbo\Zero\Services;

use Hetbo\Zero\Models\Carrot;
use Hetbo\Zero\Repositories\Contracts\CarrotRepositoryInterface;

class CarrotService {

    public function __construct(protected CarrotRepositoryInterface $carrots){}

    public function growCarrot(string $name, int $length): Carrot
    {
        return $this->carrots->create([
            'name' => $name,
            'length' => $length,
        ]);
    }

    public function getCarrot(int $id): ?Carrot
    {
        return $this->carrots->findById($id);
    }

}