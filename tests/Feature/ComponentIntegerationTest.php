<?php

use Hetbo\Zero\Models\Carrot;
use Hetbo\Zero\Tests\Models\TestModel;

beforeEach(function () {
    $this->artisan('migrate');
    $this->model = TestModel::factory()->create();
    $this->carrot = Carrot::factory()->create();
});

it('can perform full attach-detach flow', function () {
    // Start with no carrots
    expect($this->model->carrots)->toHaveCount(0);

    // Attach carrot
    $attachResponse = $this->post(route('carrots.attach', [
        'model_type' => urlencode(TestModel::class),
        'model_id' => $this->model->id
    ]), [
        'carrot_id' => $this->carrot->id,
        'role' => 'favorite'
    ]);

    $attachResponse->assertStatus(200);

    $this->model->refresh();
    expect($this->model->carrots)->toHaveCount(1);

    // Detach carrot
    $detachResponse = $this->delete(route('carrots.detach', [
        'model_type' => urlencode(TestModel::class),
        'model_id' => $this->model->id,
        'carrot_id' => $this->carrot->id,
        'role' => 'favorite'
    ]));

    $detachResponse->assertStatus(200);

    $this->model->refresh();
    expect($this->model->carrots)->toHaveCount(0);
});

it('modal updates correctly after attach/detach', function () {
    // Load modal - should show carrot as available
    $modalResponse1 = $this->get(route('carrots.modal', [
        'model_type' => urlencode(TestModel::class),
        'model_id' => $this->model->id,
        'role' => 'favorite'
    ]));

    $modalResponse1->assertSee($this->carrot->name);

    // Attach carrot
    $this->post(route('carrots.attach', [
        'model_type' => urlencode(TestModel::class),
        'model_id' => $this->model->id
    ]), [
        'carrot_id' => $this->carrot->id,
        'role' => 'favorite'
    ]);

    // Load modal again - should show carrot as attached
    $modalResponse2 = $this->get(route('carrots.modal', [
        'model_type' => urlencode(TestModel::class),
        'model_id' => $this->model->id,
        'role' => 'favorite'
    ]));

    $modalResponse2->assertViewHas('attachedCarrots', function ($attached) {
        return $attached->contains('id', $this->carrot->id);
    });
});

it('handles multiple roles correctly', function () {
    $carrot2 = Carrot::factory()->create();

    // Attach different carrots to different roles
    $this->post(route('carrots.attach', [
        'model_type' => urlencode(TestModel::class),
        'model_id' => $this->model->id
    ]), [
        'carrot_id' => $this->carrot->id,
        'role' => 'favorite'
    ]);

    $this->post(route('carrots.attach', [
        'model_type' => urlencode(TestModel::class),
        'model_id' => $this->model->id
    ]), [
        'carrot_id' => $carrot2->id,
        'role' => 'backup'
    ]);

    $this->model->refresh();

    expect($this->model->getCarrotsByRole('favorite'))->toHaveCount(1)
        ->and($this->model->getCarrotsByRole('backup'))->toHaveCount(1)
        ->and($this->model->carrots)->toHaveCount(2);
});