<?php

namespace Hetbo\Zero\Services;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Hetbo\Zero\Contracts\CarrotableRepositoryInterface;
use Hetbo\Zero\Contracts\CarrotRepositoryInterface;

class CarrotableService
{
    public function __construct(
        private CarrotableRepositoryInterface $carrotableRepository,
        private CarrotRepositoryInterface $carrotRepository
    ) {}

    public function attachCarrotToModel(Model $model, int $carrotId, string $role): void
    {
        $carrot = $this->carrotRepository->findOrFail($carrotId);
        $this->carrotableRepository->attachCarrot($model, $carrot, $role);
    }

    public function detachCarrotFromModel(Model $model, int $carrotId, ?string $role = null): void
    {
        $carrot = $this->carrotRepository->findOrFail($carrotId);
        $this->carrotableRepository->detachCarrot($model, $carrot, $role);
    }

    public function syncCarrotsForModel(Model $model, array $carrotIds, string $role): void
    {
        // Validate all carrot IDs exist
        foreach ($carrotIds as $carrotId) {
            $this->carrotRepository->findOrFail($carrotId);
        }

        $this->carrotableRepository->syncCarrots($model, $carrotIds, $role);
    }

    public function getModelCarrotsByRole(Model $model, string $role): Collection
    {
        return $this->carrotableRepository->getCarrotsByRole($model, $role);
    }

    public function getAllModelCarrots(Model $model): Collection
    {
        return $this->carrotableRepository->getAllCarrots($model);
    }

    public function getModelCarrotRoles(Model $model): Collection
    {
        return $this->carrotableRepository->getCarrotRoles($model);
    }

    public function modelHasCarrot(Model $model, int $carrotId, ?string $role = null): bool
    {
        $carrot = $this->carrotRepository->findOrFail($carrotId);
        return $this->carrotableRepository->hasCarrot($model, $carrot, $role);
    }

    public function getModelsWithCarrot(int $carrotId, ?string $modelType = null): Collection
    {
        $carrot = $this->carrotRepository->findOrFail($carrotId);
        return $this->carrotableRepository->getModelsWithCarrot($carrot, $modelType);
    }
}