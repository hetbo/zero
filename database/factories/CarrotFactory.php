<?php

namespace Hetbo\Zero\Database\Factories;

use Hetbo\Zero\Models\Carrot;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarrotFactory extends Factory
{
    protected $model = Carrot::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true) . ' Carrot',
            'length' => $this->faker->numberBetween(1, 50),
        ];
    }

    public function long(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'length' => $this->faker->numberBetween(30, 100),
            ];
        });
    }

    public function short(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'length' => $this->faker->numberBetween(1, 10),
            ];
        });
    }
}