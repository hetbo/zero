<?php

use Hetbo\Zero\DTOs\UpdateCarrotData;

it('can create from array', function () {
    $data = UpdateCarrotData::fromArray([
        'name' => 'Updated Carrot',
        'length' => 20
    ]);

    expect($data->name)->toBe('Updated Carrot')
        ->and($data->length)->toBe(20);
});

it('throws exception when name is missing', function () {
    expect(fn() => UpdateCarrotData::fromArray(['length' => 20]))
        ->toThrow(InvalidArgumentException::class, 'Name is required');
});

it('throws exception when length is missing', function () {
    expect(fn() => UpdateCarrotData::fromArray(['name' => 'Test']))
        ->toThrow(InvalidArgumentException::class, 'Length is required');
});
