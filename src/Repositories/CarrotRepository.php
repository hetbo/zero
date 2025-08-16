<?php

namespace Hetbo\Zero\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Hetbo\Zero\Models\Carrot;
use Hetbo\Zero\Contracts\CarrotRepositoryInterface;

class CarrotRepository implements CarrotRepositoryInterface
{
    public function all(): Collection
    {
        return Carrot::all();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Carrot::paginate($perPage);
    }

    public function find(int $id): ?Carrot
    {
        return Carrot::find($id);
    }

    public function findOrFail(int $id): Carrot
    {
        return Carrot::findOrFail($id);
    }

    public function create(array $data): Carrot
    {
        return Carrot::create($data);
    }

    public function update(int $id, array $data): Carrot
    {
        $carrot = $this->findOrFail($id);
        $carrot->update($data);
        return $carrot->fresh();
    }

    public function delete(int $id): bool
    {
        $carrot = $this->findOrFail($id);
        return $carrot->delete();
    }

    public function findByName(string $name): Collection
    {
        return Carrot::where('name', 'like', "%{$name}%")->get();
    }

    public function findByLengthRange(int $minLength, int $maxLength): Collection
    {
        return Carrot::whereBetween('length', [$minLength, $maxLength])->get();
    }

    public function search(string $query): Collection
    {
        return Carrot::where('name', 'like', "%{$query}%")
            ->orWhere('length', 'like', "%{$query}%")
            ->get();
    }
}