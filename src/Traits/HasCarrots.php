<?php

// src/Traits/HasCarrots.php
namespace Hetbo\Zero\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
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
     * Helper method to sync carrots for a specific role.
     */
    public function syncCarrots(array $carrotIds, string $role): void
    {
        $existing = $this->carrots()->wherePivot('role', '!=', $role)->pluck('carrot_id');
        $this->carrots()->sync(array_merge($existing->toArray(), $carrotIds));
    }

    /**
     * Helper method to get all carrots with a specific role.
     */
    public function getCarrotsByRole(string $role)
    {
        return $this->carrots()->wherePivot('role', $role)->get();
    }
}