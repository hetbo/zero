<?php

use Hetbo\Zero\Tests\Models\TestModel;
use Hetbo\Zero\Models\Carrot;

beforeEach(function () {
    $this->model = TestModel::factory()->create();
    $this->carrot = Carrot::factory()->create();
    $this->carrot2 = Carrot::factory()->create();
});

it('can attach a carrot with a role', function () {
    $this->model->attachCarrot($this->carrot, 'favorite');

    expect($this->model->carrots)->toHaveCount(1)
        ->and($this->model->carrots->first()->pivot->role)->toBe('favorite');
});

it('can get carrots by role', function () {
    $this->model->attachCarrot($this->carrot, 'favorite');
    $this->model->attachCarrot($this->carrot2, 'backup');

    $favorites = $this->model->getCarrotsByRole('favorite');
    $backups = $this->model->getCarrotsByRole('backup');

    expect($favorites)->toHaveCount(1)
        ->and($backups)->toHaveCount(1)
        ->and($favorites->first()->id)->toBe($this->carrot->id)
        ->and($backups->first()->id)->toBe($this->carrot2->id);
});

it('can check if model has carrot', function () {
    $this->model->attachCarrot($this->carrot, 'favorite');

    expect($this->model->hasCarrot($this->carrot))->toBeTrue()
        ->and($this->model->hasCarrot($this->carrot, 'favorite'))->toBeTrue()
        ->and($this->model->hasCarrot($this->carrot, 'backup'))->toBeFalse()
        ->and($this->model->hasCarrot($this->carrot2))->toBeFalse();
});

it('can detach a carrot', function () {
    $this->model->attachCarrot($this->carrot, 'favorite');
    $this->model->attachCarrot($this->carrot, 'backup');

    expect($this->model->carrots)->toHaveCount(2);

    $this->model->detachCarrot($this->carrot, 'favorite');
    $this->model->refresh();

    expect($this->model->carrots)->toHaveCount(1)
        ->and($this->model->hasCarrot($this->carrot, 'backup'))->toBeTrue();
});

it('can sync carrots for a role', function () {
    $carrot3 = Carrot::factory()->create();

    // Attach some initial carrots
    $this->model->attachCarrot($this->carrot, 'favorite');
    $this->model->attachCarrot($this->carrot2, 'backup');

    // Sync favorites
    $this->model->syncCarrots([$carrot3->id], 'favorite');
    $this->model->refresh();

    $favorites = $this->model->getCarrotsByRole('favorite');
    $backups = $this->model->getCarrotsByRole('backup');

    expect($favorites)->toHaveCount(1)
        ->and($favorites->first()->id)->toBe($carrot3->id)
        ->and($backups)->toHaveCount(1);
    // Should preserve backup role
});

it('can get carrot roles', function () {
    $this->model->attachCarrot($this->carrot, 'favorite');
    $this->model->attachCarrot($this->carrot2, 'backup');

    $roles = $this->model->getCarrotRoles();

    expect($roles)->toHaveCount(2)
        ->and($roles)->toContain('favorite')
        ->and($roles)->toContain('backup');
});