<?php

use Hetbo\Zero\Models\Carrot;
use Hetbo\Zero\Services\CarrotService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can grow and fetch a carrot', function () {
    // Resolve service from container
    $service = app(CarrotService::class);

    // Grow a carrot
    $carrot = $service->growCarrot('Orange Carrot', 15);

    expect($carrot)->toBeInstanceOf(Carrot::class)
        ->and($carrot->id)->toBeInt()
        ->and($carrot->name)->toBe('Orange Carrot')
        ->and($carrot->length)->toBe(15);

    // Fetch same carrot
    $fetched = $service->getCarrot($carrot->id);

    expect($fetched)->not()->toBeNull()
        ->and($fetched->id)->toBe($carrot->id)
        ->and($fetched->name)->toBe('Orange Carrot')
        ->and($fetched->length)->toBe(15);
});
