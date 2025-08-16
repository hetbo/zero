<?php

// tests/Feature/HasCarrotsTraitTest.php
use Illuminate\Database\Eloquent\Model;
use Hetbo\Zero\Models\Carrot;
use Hetbo\Zero\Traits\HasCarrots;

// Create a dummy model for testing
class Food extends Model {
    use HasCarrots;

    protected $table = 'foods';
    protected $guarded = [];
}

test('a model can have a carrot attached with a role', function () {
    $food = Food::create(['name' => 'Test Stew']);
    $carrot = Carrot::create(['name' => 'Stew Carrot', 'length' => 10]);

    // Use the trait method
    $food->attachCarrot($carrot, 'ingredient');

    $this->assertDatabaseHas('carrotables', [
        'carrotable_id' => $food->id,
        'carrotable_type' => Food::class,
        'carrot_id' => $carrot->id,
        'role' => 'ingredient',
    ]);
});

test('can retrieve carrots by a specific role', function () {
    $food = Food::create(['name' => 'Test Salad']);
    $saladCarrot = Carrot::create(['name' => 'Salad Carrot', 'length' => 15]);
    $garnishCarrot = Carrot::create(['name' => 'Garnish Carrot', 'length' => 5]);

    $food->attachCarrot($saladCarrot, 'salad');
    $food->attachCarrot($garnishCarrot, 'garnish');

    // Use the trait helper to fetch
    $saladCarrots = $food->getCarrotsByRole('salad');

    expect($saladCarrots)->toHaveCount(1)
        ->and($saladCarrots->first()->name)->toBe('Salad Carrot');
});

test('the http endpoint correctly attaches a carrot', function () {
    $food = Food::create(['name' => 'Test Dish']);

    $this->post(route('foods.carrots.store', $food), [
        'name' => 'Endpoint Carrot',
        'length' => 20,
        'role' => 'ingredient',
    ]);

    $this->assertDatabaseHas('carrots', ['name' => 'Endpoint Carrot']);
    $this->assertDatabaseHas('carrotables', [
        'carrotable_id' => $food->id,
        'role' => 'ingredient'
    ]);
});