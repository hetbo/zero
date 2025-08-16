<?php

namespace Hetbo\Zero\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Hetbo\Zero\Models\Carrot;

trait HasCarrots
{
    /**
     * The main relationship to get all carrots for this model.
     */
    public function carrots(): MorphToMany
    {
        return $this->morphToMany(Carrot::class, 'carrotable')->withPivot('role');
    }

    /**
     * Helper method to attach a carrot with a specific role.
     */
    public function attachCarrot(Carrot $carrot, string $role): void
    {
        $this->carrots()->attach($carrot->id, ['role' => $role]);
    }

    /**
     * Helper method to detach a carrot.
     */
    public function detachCarrot(Carrot $carrot, ?string $role = null): void
    {
        $query = $this->carrots()->wherePivot('carrot_id', $carrot->id);

        if ($role) {
            $query->wherePivot('role', $role);
        }

        $query->detach();
    }

    /**
     * Helper method to sync carrots for a specific role.
     */
    public function syncCarrots(array $carrotIds, string $role): void
    {
        // Get existing carrots with different roles to preserve them
        $existingDifferentRoles = $this->carrots()
            ->wherePivot('role', '!=', $role)
            ->pluck('carrot_id')
            ->toArray();

        // Prepare sync data with roles
        $syncData = [];

        // Add existing carrots with different roles
        foreach ($existingDifferentRoles as $carrotId) {
            $existingRole = $this->carrots()
                ->wherePivot('carrot_id', $carrotId)
                ->wherePivot('role', '!=', $role)
                ->first()
                ->pivot
                ->role;
            $syncData[$carrotId] = ['role' => $existingRole];
        }

        // Add new carrots with the specified role
        foreach ($carrotIds as $carrotId) {
            $syncData[$carrotId] = ['role' => $role];
        }

        $this->carrots()->sync($syncData);
    }

    /**
     * Helper method to get all carrots with a specific role.
     */
    public function getCarrotsByRole(string $role): Collection
    {
        return $this->carrots()->wherePivot('role', $role)->get();
    }

    /**
     * Check if model has a specific carrot with role.
     */
    public function hasCarrot(Carrot $carrot, ?string $role = null): bool
    {
        $query = $this->carrots()->where('carrot_id', $carrot->id);

        if ($role) {
            $query->wherePivot('role', $role);
        }

        return $query->exists();
    }

    /**
     * Get all unique roles for carrots on this model.
     */
    public function getCarrotRoles(): Collection
    {
        return $this->carrots()->distinct('role')->pluck('role');
    }
}
