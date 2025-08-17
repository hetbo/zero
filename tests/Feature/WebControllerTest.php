<?php

use Hetbo\Zero\Models\Carrot;
use Hetbo\Zero\Tests\Models\TestModel;

beforeEach(function () {
    $this->model = TestModel::factory()->create();
    $this->carrot = Carrot::factory()->create(['name' => 'Test Carrot', 'length' => 15]);
    $this->carrot2 = Carrot::factory()->create(['name' => 'Second Carrot', 'length' => 20]);
});

it('renders modal content correctly', function () {
    $this->model->attachCarrot($this->carrot, 'favorite');

    $response = $this->get(route('carrots.modal', [
        'model_type' => urlencode(get_class($this->model)),
        'model_id' => $this->model->id,
        'role' => 'favorite'
    ]));

    $response->assertStatus(200)
        ->assertSee('Manage Favorite Carrots')
        ->assertSee('Currently Attached (1)')
        ->assertSee('Test Carrot')
        ->assertSee('(15cm)')
        ->assertSee('Available to Attach');
});

it('renders component content partial correctly', function () {
    $this->model->attachCarrot($this->carrot, 'favorite');

    $response = $this->get(route('carrots.component-content', [
        'model_type' => urlencode(get_class($this->model)),
        'model_id' => $this->model->id,
        'role' => 'favorite'
    ]));

    $response->assertStatus(200)
        ->assertSee('Test Carrot')
        ->assertSee('(15cm)')
        ->assertSee('hx-swap="none"', false) // Use false to check unescaped HTML
        ->assertDontSee('Manage Favorite Carrots'); // Should not contain modal wrapper
});

it('attaches carrot and returns modal content with trigger', function () {
    $response = $this->post(route('carrots.attach', [
        'model_type' => urlencode(get_class($this->model)),
        'model_id' => $this->model->id,
    ]), [
        'carrot_id' => $this->carrot->id,
        'role' => 'favorite'
    ]);

    $response->assertStatus(200)
        ->assertHeader('HX-Trigger', 'carrotAttached')
        ->assertSee('Currently Attached (1)')
        ->assertSee('Test Carrot');

    expect($this->model->fresh()->getCarrotsByRole('favorite'))->toHaveCount(1);
});

it('detaches carrot and returns empty response with trigger', function () {
    $this->model->attachCarrot($this->carrot, 'favorite');

    $response = $this->delete(route('carrots.detach', [
        'model_type' => urlencode(get_class($this->model)),
        'model_id' => $this->model->id,
        'carrot_id' => $this->carrot->id,
        'role' => 'favorite'
    ]));

    $response->assertStatus(200)
        ->assertHeader('HX-Trigger', 'carrotDetached');

    expect($this->model->fresh()->getCarrotsByRole('favorite'))->toHaveCount(0);
});

it('loads more available carrots correctly', function () {
    // Create 15 carrots to test pagination
    $carrots = Carrot::factory()->count(15)->create();

    $response = $this->get(route('carrots.load-more', [
            'model_type' => urlencode(get_class($this->model)),
            'model_id' => $this->model->id,
            'role' => 'favorite'
        ]) . '?page=2');

    $response->assertStatus(200)
        ->assertSee('Attach'); // Should show attach buttons
});

it('validates attach request properly', function () {
    $response = $this->postJson(route('carrots.attach', [
        'model_type' => urlencode(get_class($this->model)),
        'model_id' => $this->model->id,
    ]), [
        'role' => 'favorite'
        // Missing carrot_id
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['carrot_id']);
});

it('modal shows correct counts and sections', function () {
    $this->model->attachCarrot($this->carrot, 'favorite');

    $response = $this->get(route('carrots.modal', [
        'model_type' => urlencode(get_class($this->model)),
        'model_id' => $this->model->id,
        'role' => 'favorite'
    ]));

    $response->assertStatus(200)
        ->assertSee('Currently Attached (1)')
        ->assertSee('Available to Attach')
        ->assertSee('Test Carrot')
        ->assertSee('Remove') // Remove button in modal
        ->assertSee('href="#"', false); // Use false to check unescaped HTML
});

it('handles empty states correctly', function () {
    // Test modal with no carrots attached
    $response = $this->get(route('carrots.modal', [
        'model_type' => urlencode(get_class($this->model)),
        'model_id' => $this->model->id,
        'role' => 'favorite'
    ]));

    $response->assertStatus(200)
        ->assertSee('Currently Attached (0)')
        ->assertSee('No carrots currently attached');

    // Test component content with no carrots
    $response = $this->get(route('carrots.component-content', [
        'model_type' => urlencode(get_class($this->model)),
        'model_id' => $this->model->id,
        'role' => 'favorite'
    ]));

    $response->assertStatus(200)
        ->assertSee('No carrots attached with role "favorite"', false); // Use false to check unescaped HTML
});