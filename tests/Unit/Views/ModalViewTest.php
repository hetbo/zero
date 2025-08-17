<?php

use Hetbo\Zero\Models\Carrot;
use Hetbo\Zero\Tests\Models\TestModel;
use Illuminate\Support\Facades\View;

beforeEach(function () {
    $this->model = TestModel::factory()->create();
    $this->attachedCarrot = Carrot::factory()->create(['name' => 'Attached Carrot']);
    $this->availableCarrot = Carrot::factory()->create(['name' => 'Available Carrot']);

    $this->model->attachCarrot($this->attachedCarrot, 'favorite');
});

it('renders modal view correctly', function () {
    $view = View::make('zero::carrots.modal', [
        'model' => $this->model,
        'modelType' => urlencode(TestModel::class),
        'role' => 'favorite',
        'attachedCarrots' => collect([$this->attachedCarrot]),
        'availableCarrots' => collect([$this->availableCarrot]),
        'hasMore' => false
    ]);

    $html = $view->render();

    expect($html)->toContain('Manage Favorite Carrots')
        ->toContain('Currently Attached (1)')
        ->toContain('Attached Carrot')
        ->toContain('Available Carrot')
        ->toContain('Available to Attach');
});

it('shows empty attached state correctly', function () {
    $view = View::make('zero::carrots.modal', [
        'model' => $this->model,
        'modelType' => urlencode(TestModel::class),
        'role' => 'empty',
        'attachedCarrots' => collect([]),
        'availableCarrots' => collect([$this->availableCarrot]),
        'hasMore' => false
    ]);

    $html = $view->render();

    expect($html)->toContain('No carrots currently attached');
});

it('includes correct detach urls', function () {
    $view = View::make('zero::carrots.modal', [
        'model' => $this->model,
        'modelType' => urlencode(TestModel::class),
        'role' => 'favorite',
        'attachedCarrots' => collect([$this->attachedCarrot]),
        'availableCarrots' => collect([]),
        'hasMore' => false
    ]);

    $html = $view->render();

    $expectedUrl = route('carrots.detach', [
        'model_type' => urlencode(TestModel::class),
        'model_id' => $this->model->id,
        'carrot_id' => $this->attachedCarrot->id,
        'role' => 'favorite'
    ]);

    expect($html)->toContain($expectedUrl);
});