<?php

use Hetbo\Zero\DTOs\CreateCarrotData;

it('can create from array', function () {
    $data = CreateCarrotData::fromArray([
        'name' => 'Test Carrot',
        'length' => 15
    ]);

    expect($data->name)->toBe('Test Carrot')
        ->and($data->length)->toBe(15);
});

it('throws exception when name is missing', function () {
    expect(fn() => CreateCarrotData::fromArray(['length' => 15]))
        ->toThrow(InvalidArgumentException::class, 'Name is required');
});

it('throws exception when length is missing', function () {
    expect(fn() => CreateCarrotData::fromArray(['name' => 'Test']))
        ->toThrow(InvalidArgumentException::class, 'Length is required');
});

it('casts length to integer', function () {
    $data = CreateCarrotData::fromArray([
        'name' => 'Test Carrot',
        'length' => '15'
    ]);

    expect($data->length)->toBeInt()
        ->and($data->length)->toBe(15);
});