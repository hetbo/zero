<?php

use Hetbo\Zero\Repositories\CarrotableRepository;
use Hetbo\Zero\Models\Carrot;
use Hetbo\Zero\Tests\Models\TestModel;
use Illuminate\Database\Eloquent\Model;

beforeEach(function () {
    $this->repository = new CarrotableRepository();
    $this->model = TestModel::factory()->create();
    $this->carrot = Carrot::factory()->create();
});

it('can attach carrot to model', function () {
    $this->repository->attachCarrot($this->model, $this->carrot, 'favorite');

    expect($this->model->carrots)->toHaveCount(1);
});

it('throws exception when model does not use HasCarrots trait', function () {
    $model = new class extends Model {};

    expect(fn() => $this->repository->attachCarrot($model, $this->carrot, 'favorite'))
        ->toThrow(InvalidArgumentException::class, 'Model must use HasCarrots trait');
});

it('can detach carrot from model', function () {
    $this->model->attachCarrot($this->carrot, 'favorite');

    expect($this->model->carrots)->toHaveCount(1);

    $this->repository->detachCarrot($this->model, $this->carrot);
    $this->model->refresh();

    expect($this->model->carrots)->toHaveCount(0);
});

it('can sync carrots for model', function () {
    $carrot2 = Carrot::factory()->create();
    $carrot3 = Carrot::factory()->create();

    $this->repository->syncCarrots($this->model, [$carrot2->id, $carrot3->id], 'favorite');
    $this->model->refresh();

    expect($this->model->carrots)->toHaveCount(2);
});

it('can get carrots by role', function () {
    $this->model->attachCarrot($this->carrot, 'favorite');

    $carrots = $this->repository->getCarrotsByRole($this->model, 'favorite');

    expect($carrots)->toHaveCount(1)
        ->and($carrots->first()->id)->toBe($this->carrot->id);
});

it('can check if model has carrot', function () {
    $this->model->attachCarrot($this->carrot, 'favorite');

    $result = $this->repository->hasCarrot($this->model, $this->carrot, 'favorite');

    expect($result)->toBeTrue();
});