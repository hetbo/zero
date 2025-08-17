<?php

use Hetbo\Zero\Models\Carrot;
use Hetbo\Zero\Tests\Models\TestModel;
use Illuminate\Support\Facades\View;

beforeEach(function () {
    $this->model = TestModel::factory()->create();
    $this->carrot1 = Carrot::factory()->create(['name' => 'Carrot 1']);
    $this->carrot2 = Carrot::factory()->create(['name' => 'Carrot 2']);
});

it('renders available carrots partial correctly', function () {
    $view = View::make('zero::carrots.partials.available-carrots', [
        'availableCarrots' => collect([$this->carrot1, $this->carrot2]),
        'modelType' => urlencode(TestModel::class),
        'modelId' => $this->model->id,
        'role' => 'favorite',
        'page' => 1,
        'hasMore' => true
    ]);

    $html = $view->render();

    expect($html)->toContain('Carrot 1')
        ->toContain('Carrot 2')
        ->toContain('Load More')
        ->toContain('Attach');
});

it('shows no more carrots message when none available', function () {
    $view = View::make('zero::carrots.partials.available-carrots', [
        'availableCarrots' => collect([]),
        'modelType' => urlencode(TestModel::class),
        'modelId' => $this->model->id,
        'role' => 'favorite',
        'page' => 1,
        'hasMore' => false
    ]);

    $html = $view->render();

    expect($html)->toContain('No more carrots available to attach');
});

it('does not show load more when no more pages', function () {
    $view = View::make('zero::carrots.partials.available-carrots', [
        'availableCarrots' => collect([$this->carrot1]),
        'modelType' => urlencode(TestModel::class),
        'modelId' => $this->model->id,
        'role' => 'favorite',
        'page' => 1,
        'hasMore' => false
    ]);

    $html = $view->render();

    expect($html)->not->toContain('Load More');
});

it('includes correct attach urls', function () {
    $view = View::make('zero::carrots.partials.available-carrots', [
        'availableCarrots' => collect([$this->carrot1]),
        'modelType' => urlencode(TestModel::class),
        'modelId' => $this->model->id,
        'role' => 'favorite',
        'page' => 1,
        'hasMore' => false
    ]);

    $html = $view->render();

    $expectedUrl = route('carrots.attach', [
        'model_type' => urlencode(TestModel::class),
        'model_id' => $this->model->id
    ]);

    expect($html)->toContain($expectedUrl)
        ->toContain('"carrot_id": ' . $this->carrot1->id)
        ->toContain('"role": "favorite"');
});