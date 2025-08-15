<?php

use Hetbo\Zero\Facades\Calculator;

it('can perform chained calculations', function () {
    $result = Calculator::add(5)->subtract(3)->getResult();

    expect($result)->toBe(2);
});

it('can handle multiple operations', function () {
    $result = Calculator::add(10)->subtract(3)->add(5)->getResult();

    expect($result)->toBe(12);
});

it('starts with zero by default', function () {
    $result = Calculator::getResult();

    expect($result)->toBe(0);
});

it('can reset and start fresh', function () {
    Calculator::add(100);
    $result = Calculator::clear()->add(5)->getResult();

    expect($result)->toBe(5);
});