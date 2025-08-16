<?php

namespace Hetbo\Zero\Services;

use Hetbo\Zero\Contracts\CarrotRepositoryInterface;
use Hetbo\Zero\Models\Carrot;

class CarrotService
{
    public function __construct(protected CarrotRepositoryInterface $carrotRepository) {}

    /**
     * Find a carrot by its name, or create it if it doesn't exist.
     */
    public function findOrCreate(string $name, int $length): Carrot
    {
        $carrot = $this->carrotRepository->findByName($name);

        if ($carrot) {
            return $carrot;
        }

        return $this->carrotRepository->create(['name' => $name, 'length' => $length]);
    }
}