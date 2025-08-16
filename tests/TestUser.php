<?php

namespace Hetbo\Zero\Tests;

use Hetbo\Zero\Contracts\UserContract;
use Hetbo\Zero\Models\Carrot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TestUser extends Authenticatable implements UserContract
{
    use HasFactory;

    protected $guarded = []; // Allow mass assignment for tests
    protected $table = 'users';

    public function carrots(): HasMany
    {
        return $this->hasMany(Carrot::class, 'user_id');
    }

    // Dummy factory for creating users in tests
    protected static function newFactory()
    {
        return TestUserFactory::new();
    }
}

// You can define the factory right in the file for simplicity
use Illuminate\Database\Eloquent\Factories\Factory;

class TestUserFactory extends Factory
{
    protected $model = TestUser::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
        ];
    }
}