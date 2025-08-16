<?php

use Hetbo\Zero\Repositories\CarrotRepository;
use Hetbo\Zero\Models\Carrot;

beforeEach(function () {
    $this->repository = new CarrotRepository();
});

it('can get all carrots', function () {
    Carrot::factory(3)->create();

    $carrots = $this->repository->all();

    expect($carrots)->toHaveCount(3);
});

it('can paginate carrots', function () {
    Carrot::factory(25)->create();

    $paginatedCarrots = $this->repository->paginate(10);

    expect($paginatedCarrots->count())->toBe(10)
        ->and($paginatedCarrots->total())->toBe(25)
        ->and($paginatedCarrots->hasPages())->toBeTrue();
});

it('can find a carrot by id', function () {
    $carrot = Carrot::factory()->create();

    $found = $this->repository->find($carrot->id);

    expect($found)->not->toBeNull()
        ->and($found->id)->toBe($carrot->id);
});

it('returns null when carrot not found', function () {
    $found = $this->repository->find(999);

    expect($found)->toBeNull();
});

it('can create a carrot', function () {
    $data = [
        'name' => 'New Carrot',
        'length' => 20
    ];

    $carrot = $this->repository->create($data);

    expect($carrot->name)->toBe('New Carrot')
        ->and($carrot->length)->toBe(20)
        ->and($carrot->exists)->toBeTrue();
});

it('can update a carrot', function () {
    $carrot = Carrot::factory()->create(['name' => 'Old Name']);

    $updated = $this->repository->update($carrot->id, ['name' => 'New Name']);

    expect($updated->name)->toBe('New Name')
        ->and($updated->id)->toBe($carrot->id);
});

it('can delete a carrot', function () {
    $carrot = Carrot::factory()->create();

    $result = $this->repository->delete($carrot->id);

    expect($result)->toBeTrue()
        ->and(Carrot::find($carrot->id))->toBeNull();
});

it('can find carrots by name', function () {
    Carrot::factory()->create(['name' => 'Big Carrot']);
    Carrot::factory()->create(['name' => 'Small Carrot']);
    Carrot::factory()->create(['name' => 'Radish']);

    $carrots = $this->repository->findByName('Carrot');

    expect($carrots)->toHaveCount(2);
});

it('can find carrots by length range', function () {
    Carrot::factory()->create(['length' => 5]);
    Carrot::factory()->create(['length' => 15]);
    Carrot::factory()->create(['length' => 25]);

    $carrots = $this->repository->findByLengthRange(10, 20);

    expect($carrots)->toHaveCount(1)
        ->and($carrots->first()->length)->toBe(15);
});

it('can search carrots', function () {
    Carrot::factory()->create(['name' => 'Big Carrot', 'length' => 10]);
    Carrot::factory()->create(['name' => 'Small Potato', 'length' => 15]);

    $carrots = $this->repository->search('Big');

    expect($carrots)->toHaveCount(1)
        ->and($carrots->first()->name)->toBe('Big Carrot');
});