<?php

use Hetbo\Zero\Services\CarrotService;
use Hetbo\Zero\Contracts\CarrotRepositoryInterface;
use Hetbo\Zero\Models\Carrot;
use Hetbo\Zero\DTOs\CreateCarrotData;
use Hetbo\Zero\DTOs\UpdateCarrotData;

beforeEach(function () {
    $this->repository = Mockery::mock(CarrotRepositoryInterface::class);
    $this->service = new CarrotService($this->repository);
});

afterEach(function () {
    Mockery::close();
});

it('can get all carrots', function () {
    $carrots = collect([new Carrot(), new Carrot()]);

    $this->repository
        ->shouldReceive('all')
        ->once()
        ->andReturn($carrots);

    $result = $this->service->getAllCarrots();

    expect($result)->toBe($carrots);
});

it('can create carrot', function () {
    $data = new CreateCarrotData('Test Carrot', 10);
    $carrot = new Carrot();

    $this->repository
        ->shouldReceive('create')
        ->once()
        ->with(['name' => 'Test Carrot', 'length' => 10])
        ->andReturn($carrot);

    $result = $this->service->createCarrot($data);

    expect($result)->toBe($carrot);
});

it('can update carrot', function () {
    $data = new UpdateCarrotData('Updated Carrot', 15);
    $carrot = new Carrot();

    $this->repository
        ->shouldReceive('update')
        ->once()
        ->with(1, ['name' => 'Updated Carrot', 'length' => 15])
        ->andReturn($carrot);

    $result = $this->service->updateCarrot(1, $data);

    expect($result)->toBe($carrot);
});

it('throws exception for invalid length range', function () {
    expect(fn() => $this->service->findCarrotsByLengthRange(10, 5))
        ->toThrow(InvalidArgumentException::class, 'Minimum length cannot be greater than maximum length');
});