<?php

use Hetbo\Zero\Models\Carrot;
use Hetbo\Zero\Tests\Models\TestModel;
use Illuminate\Support\Facades\View;

beforeEach(function () {
    $this->model = TestModel::factory()->create();
    $this->carrot = Carrot::factory()->create(['name' => 'Test Carrot']);
});

it('renders carrot component correctly', function () {
    $this->model->attachCarrot($this->carrot, 'favorite');

    $view = View::make('zero::components.carrot', [
        'model' => $this->model,
        'role' => 'favorite'
    ]);

    $html = $view->render();

    expect($html)->toContain('favorite Carrots') // lowercase "favorite" not "Favorite"
    ->toContain('Test Carrot')
        ->toContain('Manage Carrots')
        ->toContain('href="#carrot-modal-' . $this->model->id . '-favorite"');
});

it('shows empty state when no carrots attached', function () {
    $view = View::make('zero::components.carrot', [
        'model' => $this->model,
        'role' => 'favorite'
    ]);

    $html = $view->render();

    expect($html)->toContain('No carrots attached with role "favorite"');
});

/*it('includes htmx script and styles only once', function () {
    $view1 = View::make('zero::components.carrot', [
        'model' => $this->model,
        'role' => 'favorite'
    ]);

    $view2 = View::make('zero::components.carrot', [
        'model' => $this->model,
        'role' => 'backup'
    ]);

    $html1 = $view1->render();
    $html2 = $view2->render();

    // First render should include script and styles (check actual route name)
    expect($html1)->toContain('carrot-package/carrots.js') // actual URL, not route name
    ->toContain('.htmx-modal-container')
        ->and($html2)->not->toContain('carrot-package/carrots.js')
        ->not->toContain('.htmx-modal-container');

    // Second render should not include script/styles due to @once
});*/

it('generates correct htmx attributes for component refresh', function () {
    $view = View::make('zero::components.carrot', [
        'model' => $this->model,
        'role' => 'favorite'
    ]);

    $html = $view->render();

    expect($html)->toContain('hx-trigger="carrotAttached from:body, carrotDetached from:body"')
        ->toContain('hx-target="#carrot-content-' . $this->model->id . '-favorite"')
        ->toContain('component-content'); // check for URL path, not route name
});

it('generates correct modal trigger attributes', function () {
    $view = View::make('zero::components.carrot', [
        'model' => $this->model,
        'role' => 'favorite'
    ]);

    $html = $view->render();

    expect($html)->toContain('hx-trigger="click once"')
        ->toContain('hx-target="#carrot-modal-' . $this->model->id . '-favorite .htmx-modal-content"')
        ->toContain('/modal/'); // check for URL path, not route name
});

it('creates unique modal container for each instance', function () {
    $model2 = TestModel::factory()->create();

    $view1 = View::make('zero::components.carrot', [
        'model' => $this->model,
        'role' => 'favorite'
    ]);

    $view2 = View::make('zero::components.carrot', [
        'model' => $model2,
        'role' => 'backup'
    ]);

    $html1 = $view1->render();
    $html2 = $view2->render();

    expect($html1)->toContain('id="carrot-modal-' . $this->model->id . '-favorite"')
        ->and($html2)->toContain('id="carrot-modal-' . $model2->id . '-backup"');
});

it('includes component content partial', function () {
    $this->model->attachCarrot($this->carrot, 'favorite');

    $view = View::make('zero::components.carrot', [
        'model' => $this->model,
        'role' => 'favorite'
    ]);

    $html = $view->render();

    expect($html)->toContain('Test Carrot')
        ->toContain('hx-swap="none"'); // Delete buttons should have hx-swap="none"
});