<?php

namespace Hetbo\Zero\Repositories;

use Illuminate\Database\Eloquent\Model;
use Hetbo\Zero\Models\Carrot;
use Hetbo\Zero\Contracts\CarrotableRepositoryInterface;
use Hetbo\Zero\Traits\HasCarrots;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CarrotableRepository implements CarrotableRepositoryInterface
{
    public function attachCarrot(Model $model, Carrot $carrot, string $role): void
    {
        if (!$this->modelHasCarrotsTrait($model)) {
            throw new InvalidArgumentException('Model must use HasCarrots trait');
        }

        $model->attachCarrot($carrot, $role);
    }

    public function detachCarrot(Model $model, Carrot $carrot, ?string $role = null): void
    {
        if (!$this->modelHasCarrotsTrait($model)) {
            throw new InvalidArgumentException('Model must use HasCarrots trait');
        }

        $model->detachCarrot($carrot, $role);
    }

    public function syncCarrots(Model $model, array $carrotIds, string $role): void
    {
        if (!$this->modelHasCarrotsTrait($model)) {
            throw new InvalidArgumentException('Model must use HasCarrots trait');
        }

        $model->syncCarrots($carrotIds, $role);
    }

    public function getCarrotsByRole(Model $model, string $role): Collection
    {
        if (!$this->modelHasCarrotsTrait($model)) {
            throw new InvalidArgumentException('Model must use HasCarrots trait');
        }

        return $model->getCarrotsByRole($role);
    }

    public function getAllCarrots(Model $model): Collection
    {
        if (!$this->modelHasCarrotsTrait($model)) {
            throw new InvalidArgumentException('Model must use HasCarrots trait');
        }

        return $model->carrots;
    }

    public function getCarrotRoles(Model $model): Collection
    {
        if (!$this->modelHasCarrotsTrait($model)) {
            throw new InvalidArgumentException('Model must use HasCarrots trait');
        }

        return $model->getCarrotRoles();
    }

    public function hasCarrot(Model $model, Carrot $carrot, ?string $role = null): bool
    {
        if (!$this->modelHasCarrotsTrait($model)) {
            throw new InvalidArgumentException('Model must use HasCarrots trait');
        }

        return $model->hasCarrot($carrot, $role);
    }

    public function getModelsWithCarrot(Carrot $carrot, ?string $modelType = null): Collection
    {
        $query = DB::table('carrotables')
            ->where('carrot_id', $carrot->id);

        if ($modelType) {
            $query->where('carrotable_type', $modelType);
        }

        return collect($query->get());
    }

    private function modelHasCarrotsTrait(Model $model): bool
    {
        return in_array(HasCarrots::class, class_uses_recursive($model));
    }
}