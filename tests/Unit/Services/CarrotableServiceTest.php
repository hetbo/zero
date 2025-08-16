<?php

use Hetbo\Zero\Services\CarrotableService;
use Hetbo\Zero\Contracts\CarrotableRepositoryInterface;
use Hetbo\Zero\Contracts\CarrotRepositoryInterface;
use Hetbo\Zero\Models\Carrot;
use Hetbo\Zero\Tests\Models\TestModel;

beforeEach(function () {
    $this->carrotableRepository = Mockery::mock(CarrotableRepositoryInterface::class);
    $this->carrotRepository = Mockery::mock(CarrotRepositoryInterface::class);
    $this->service = new CarrotableService($this->carrotableRepository, $this->carrotRepository);
    $this->model = new TestModel();
    $this->carrot = new Carrot();
    $this->carrot->id = 1;
});

afterEach(function () {
    Mockery::close();
});

it('can attach carrot to model', function () {
    $this->carrotRepository
        ->shouldReceive('findOrFail')
        ->once()
        ->with(1)
        ->andReturn($this->carrot);

    $this->carrotableRepository
        ->shouldReceive('attachCarrot')
        ->once()
        ->with($this->model, $this->carrot, 'favorite');

    $this->service->attachCarrotToModel($this->model, 1, 'favorite');
});

it('can sync carrots for model', function () {
    $carrotIds = [1, 2, 3];

    foreach ($carrotIds as $id) {
        $carrot = new Carrot();
        $carrot->id = $id;

        $this->carrotRepository
            ->shouldReceive('findOrFail')
            ->once()
            ->with($id)
            ->andReturn($carrot);
    }

    $this->carrotableRepository
        ->shouldReceive('syncCarrots')
        ->once()
        ->with($this->model, $carrotIds, 'favorite');

    $this->service->syncCarrotsForModel($this->model, $carrotIds, 'favorite');
});