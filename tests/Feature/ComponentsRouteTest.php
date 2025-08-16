<?php

use Hetbo\Zero\Models\Carrot;
use Hetbo\Zero\Traits\HasCarrots;
use Illuminate\Database\Eloquent\Model;

// Best Practice: Define the dummy class once at the top of the file.
class Food extends Model {
    use HasCarrots;
    protected $table = 'foods';
    protected $guarded = [];
}

// beforeEach will run before EACH test in this file.
beforeEach(function (){
    // Attach the variables to the test case object using `$this`.
    $this->food = Food::create(['name' => 'Test Stew']);
    $this->carrot = Carrot::create(['name' => 'Test Carrot', 'length' => 10]);
});

test('can attach a carrot via the component route', function () {
    // Access the variables via `$this`.
    $this->post(route('carrots-package.attach'), [
        'model_type' => Food::class,
        'model_id' => $this->food->id,
        'role' => 'ingredient',
        'carrot_id' => $this->carrot->id,
    ]);

    $this->assertDatabaseHas('carrotables', [
        'carrotable_id' => $this->food->id,
        'carrotable_type' => Food::class,
        'carrot_id' => $this->carrot->id,
        'role' => 'ingredient',
    ]);
});

test('can detach a carrot via the component route', function () {
    // First, attach the carrot for the setup of this specific test.
    $this->food->attachCarrot($this->carrot, 'ingredient');

    // Now, test the detach route.
    $this->post(route('carrots-package.detach'), [
        'model_type' => Food::class,
        'model_id' => $this->food->id,
        'role' => 'ingredient',
        'carrot_id' => $this->carrot->id,
    ]);

    $this->assertDatabaseMissing('carrotables', [
        'carrot_id' => $this->carrot->id,
        'carrotable_id' => $this->food->id,
    ]);
});