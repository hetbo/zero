<?php


namespace Hetbo\Zero\Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Hetbo\Zero\Models\Carrot;
use Hetbo\Zero\Traits\HasCarrots;

// --- DUMMY MODEL SETUP ---
// Define the dummy model our tests will use.
class Food extends Model
{
    use HasCarrots;

    protected $table = 'foods';
    protected $guarded = [];
}

// --- TEST SETUP ---
// Use `beforeEach` to create fresh models for every test.
beforeEach(function () {
    $this->food = Food::create(['name' => 'Test Stew']);
    $this->carrot = Carrot::create(['name' => 'Test Carrot', 'length' => 10]);
});


// --- THE TESTS ---

// ... other tests ...

test('the asset route serves javascript content', function () {
    // --- SETUP ---
    // 1. Define the path where the controller expects the file.
    //    We need to place a fake file here for the test to pass.
    $distPath = __DIR__ . '/../../dist';
    $assetPath = $distPath . '/carrots.js';

    // 2. Create the directory if it doesn't exist.
    if (!is_dir($distPath)) {
        mkdir($distPath, 0755, true);
    }

    // 3. Create a fake asset file. The content doesn't matter.
    file_put_contents($assetPath, '// Fake JS content');


    // --- ACTION & ASSERTION ---
    // Now the controller can find the file.
    $this->get(route('carrot-package.assets.js'))
        ->assertOk()
        ->assertHeader('Content-Type', 'application/javascript');


    // --- TEARDOWN ---
    // 4. Clean up the fake file and directory so it doesn't affect other things.
    unlink($assetPath);
    rmdir($distPath);
});
test('the attach route correctly attaches a carrot to a model', function () {
    $this->post(route('carrot-package.attach'), [
        'model_type' => Food::class,
        'model_id' => $this->food->id,
        'role' => 'ingredient',
        'carrot_id' => $this->carrot->id,
    ])->assertOk();

    $this->assertDatabaseHas('carrotables', [
        'carrotable_id' => $this->food->id,
        'carrotable_type' => Food::class,
        'carrot_id' => $this->carrot->id,
        'role' => 'ingredient',
    ]);
});

test('the attach route returns the re-rendered component html', function () {
    $response = $this->post(route('carrot-package.attach'), [
        'model_type' => Food::class,
        'model_id' => $this->food->id,
        'role' => 'ingredient',
        'carrot_id' => $this->carrot->id,
    ]);

    $response->assertOk()
        // Check that the response contains the outer div of the component
        ->assertSee('<div id="carrot-manager-ingredient-' . $this->food->id . '"', false)
        // Check that the response now includes the name of the attached carrot
        ->assertSee('Test Carrot');
});

test('the detach route correctly detaches a carrot from a model', function () {
    // First, attach the carrot so we can test detaching it.
    $this->food->attachCarrot($this->carrot, 'ingredient');

    $this->assertDatabaseHas('carrotables', ['carrot_id' => $this->carrot->id]);

    // Now, test the detach endpoint.
    // Note: We're using hx-vals, so the data might come in the body or query string.
    // Pest's `post` method handles this fine.
    $response = $this->post(route('carrot-package.detach'), [
        'model_type' => Food::class,
        'model_id' => $this->food->id,
        'role' => 'ingredient',
        'carrot_id' => $this->carrot->id,
    ]);

    $response->assertOk();
    $this->assertDatabaseMissing('carrotables', [
        'carrotable_id' => $this->food->id,
        'carrot_id' => $this->carrot->id,
    ]);
});

test('the detach route returns the re-rendered component html without the carrot', function () {
    $this->food->attachCarrot($this->carrot, 'ingredient');

    $response = $this->post(route('carrot-package.detach'), [
        'model_type' => Food::class,
        'model_id' => $this->food->id,
        'role' => 'ingredient',
        'carrot_id' => $this->carrot->id,
    ]);

    $response->assertOk()
        ->assertSee('No carrots assigned to this role.')
        ->assertDontSee('Test Carrot');
});

test('the controller gracefully fails for an invalid model type', function () {
    $this->post(route('carrot-package.attach'), [
        'model_type' => 'App\\Invalid\\Model', // A class that doesn't exist
        'model_id' => 1,
        'role' => 'ingredient',
        'carrot_id' => $this->carrot->id,
    ])->assertStatus(500); // Or whatever status code you chose for the abort
});

// ... (at the end of the file with your other tests)

test('the attach route response does not contain the full page layout', function () {
    $response = $this->post(route('carrot-package.attach'), [
        'model_type' => Food::class,
        'model_id' => $this->food->id,
        'role' => 'ingredient',
        'carrot_id' => $this->carrot->id,
    ]);

    // The test passes if the response is a clean HTML fragment.
    $response->assertOk()
        // It SHOULD contain the component's root div.
        ->assertSee('<div id="carrot-manager-ingredient-'.$this->food->id.'"', false)
        // But it should NOT contain the main layout tags.
        ->assertDontSee('<html>')
        ->assertDontSee('<body>');
});

test('the detach route response does not contain the full page layout', function () {
    // Setup: Attach a carrot first
    $this->food->attachCarrot($this->carrot, 'ingredient');

    $response = $this->post(route('carrot-package.detach'), [
        'model_type' => Food::class,
        'model_id' => $this->food->id,
        'role' => 'ingredient',
        'carrot_id' => $this->carrot->id,
    ]);

    $response->assertOk()
        ->assertDontSee('<html>')
        ->assertDontSee('<body>');
});

// tests/Feature/ComponentHttpTest.php

test('it shows an error when attaching a carrot that is already attached', function () {
    // Setup: Attach the carrot first
    $this->food->attachCarrot($this->carrot, 'ingredient');

    // Action: Try to attach the SAME carrot with the SAME role
    $response = $this->post(route('carrot-package.attach'), [
        'model_type' => Food::class,
        'model_id' => $this->food->id,
        'role' => 'ingredient',
        'carrot_id' => $this->carrot->id,
    ]);

    // Assertion: Check for the custom validation rule's message
    $response->assertOk()
        ->assertSee('This carrot is already attached with this role.');

    // Also assert that the carrot was not attached a second time
    expect($this->food->getCarrotsByRole('ingredient'))->toHaveCount(1);
});