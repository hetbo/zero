<?php

use Hetbo\Zero\Models\ZeroLog;

beforeEach(function () {
    $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    $this->artisan('migrate');
});

it('can create a zero log', function () {
    $log = ZeroLog::create([
        'action' => 'test_action',
        'data' => ['key' => 'value']
    ]);

    expect($log->action)->toBe('test_action')
        ->and($log->data)->toBe(['key' => 'value'])
        ->and($log->exists)->toBeTrue();
});

it('casts data to array', function () {
    $log = new ZeroLog([
        'action' => 'test',
        'data' => ['foo' => 'bar']
    ]);

    expect($log->data)->toBeArray()
        ->and($log->data['foo'])->toBe('bar');
});

it('can retrieve logs from database', function () {
    ZeroLog::create(['action' => 'first', 'data' => ['test' => 1]]);
    ZeroLog::create(['action' => 'second', 'data' => ['test' => 2]]);

    $logs = ZeroLog::all();

    expect($logs)->toHaveCount(2)
        ->and($logs->first()->action)->toBe('first');
});