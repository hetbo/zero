<?php

namespace Hetbo\Zero\Services;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Hetbo\Zero\Models\Carrot;
use Hetbo\Zero\Contracts\CarrotRepositoryInterface;
use Hetbo\Zero\DTOs\CreateCarrotData;
use Hetbo\Zero\DTOs\UpdateCarrotData;

class CarrotService
{
    public function __construct(
        private CarrotRepositoryInterface $carrotRepository
    ) {}

    public function getAllCarrots(): Collection
    {
        return $this->carrotRepository->all();
    }

    public function getPaginatedCarrots(int $perPage = 15): LengthAwarePaginator
    {
        return $this->carrotRepository->paginate($perPage);
    }

    public function getCarrot(int $id): ?Carrot
    {
        return $this->carrotRepository->find($id);
    }

    public function getCarrotOrFail(int $id): Carrot
    {
        return $this->carrotRepository->findOrFail($id);
    }

    public function createCarrot(CreateCarrotData $data): Carrot
    {
        return $this->carrotRepository->create([
            'name' => $data->name,
            'length' => $data->length,
        ]);
    }

    public function updateCarrot(int $id, UpdateCarrotData $data): Carrot
    {
        return $this->carrotRepository->update($id, [
            'name' => $data->name,
            'length' => $data->length,
        ]);
    }

    public function deleteCarrot(int $id): bool
    {
        return $this->carrotRepository->delete($id);
    }

    public function searchCarrots(string $query): Collection
    {
        return $this->carrotRepository->search($query);
    }

    public function findCarrotsByName(string $name): Collection
    {
        return $this->carrotRepository->findByName($name);
    }

    public function findCarrotsByLengthRange(int $minLength, int $maxLength): Collection
    {
        if ($minLength > $maxLength) {
            throw new \InvalidArgumentException('Minimum length cannot be greater than maximum length');
        }

        return $this->carrotRepository->findByLengthRange($minLength, $maxLength);
    }
}