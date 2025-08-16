<?php

namespace Hetbo\Zero\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class CarrotNotAttached implements Rule
{
    protected Model $model;
    protected string $role;

    public function __construct(Model $model, string $role)
    {
        $this->model = $model;
        $this->role = $role;
    }

    public function passes($attribute, $value): bool
    {
        return ! $this->model->carrots()
            ->wherePivot('role', $this->role)
            ->where('carrot_id', $value)
            ->exists();
    }

    public function message(): string
    {
        return 'This carrot is already attached with this role.';
    }
}