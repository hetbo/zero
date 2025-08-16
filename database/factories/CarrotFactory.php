<?php

namespace Hetbo\Zero\Database\Factories;

use Hetbo\Zero\Models\Carrot;
use Hetbo\Zero\Tests\TestUser; // We need our TestUser for relationships
use Illuminate\Database\Eloquent\Factories\Factory;

class CarrotFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Carrot::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word() . ' Carrot',
            'length' => $this->faker->numberBetween(5, 30),
            'user_id' => TestUser::factory(), // Automatically create a new user for the carrot
        ];
    }
}