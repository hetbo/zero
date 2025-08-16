<?php

namespace Hetbo\Zero\Contracts;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Hetbo\Zero\Models\Carrot;

interface CarrotableRepositoryInterface
{
    public function attachCarrot(Model $model, Carrot $carrot, string $role): void;

    public function detachCarrot(Model $model, Carrot $carrot, ?string $role = null): void;

    public function syncCarrots(Model $model, array $carrotIds, string $role): void;

    public function getCarrotsByRole(Model $model, string $role): Collection;

    public function getAllCarrots(Model $model): Collection;

    public function getCarrotRoles(Model $model): Collection;

    public function hasCarrot(Model $model, Carrot $carrot, ?string $role = null): bool;

    public function getModelsWithCarrot(Carrot $carrot, ?string $modelType = null): Collection;
}
