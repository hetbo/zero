<?php

use Hetbo\Zero\Models\Carrot;

it('can create a carrot', function () {
    $carrot = Carrot::create([
        'name' => 'Test Carrot',
        'length' => 10
    ]);

    expect($carrot->name)->toBe('Test Carrot')
        ->and($carrot->length)->toBe(10)
        ->and($carrot->exists)->toBeTrue();
});

it('casts length to integer', function () {
    $carrot = Carrot::create([
        'name' => 'Test Carrot',
        'length' => '15'
    ]);

    expect($carrot->length)->toBeInt()
        ->and($carrot->length)->toBe(15);
});