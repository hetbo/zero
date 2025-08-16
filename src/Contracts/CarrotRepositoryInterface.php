<?php

namespace Hetbo\Zero\Contracts;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Hetbo\Zero\Models\Carrot;

interface CarrotRepositoryInterface
{
    public function all(): Collection;

    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Carrot;

    public function findOrFail(int $id): Carrot;

    public function create(array $data): Carrot;

    public function update(int $id, array $data): Carrot;

    public function delete(int $id): bool;

    public function findByName(string $name): Collection;

    public function findByLengthRange(int $minLength, int $maxLength): Collection;

    public function search(string $query): Collection;
}
