<?php

use Hetbo\Zero\Models\Carrot;
use Hetbo\Zero\Tests\Models\TestModel;

beforeEach(function () {
    $this->artisan('migrate');
    $this->model = TestModel::factory()->create();
    $this->carrot = Carrot::factory()->create();
});

it('can attach carrot to model', function () {
    $data = [
        'model_type' => TestModel::class,
        'model_id' => $this->model->id,
        'carrot_id' => $this->carrot->id,
        'role' => 'favorite'
    ];

    $response = $this->postJson('/api/carrotables/attach', $data);

    $response->assertStatus(200)
        ->assertJsonPath('message', 'Carrot attached successfully');

    $this->model->refresh();
    expect($this->model->carrots)->toHaveCount(1);
});

it('validates attach request', function () {
    $response = $this->postJson('/api/carrotables/attach', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['model_type', 'model_id', 'carrot_id', 'role']);
});

it('can detach carrot from model', function () {
    $this->model->attachCarrot($this->carrot, 'favorite');

    $data = [
        'model_type' => TestModel::class,
        'model_id' => $this->model->id,
        'carrot_id' => $this->carrot->id,
        'role' => 'favorite'
    ];

    $response = $this->postJson('/api/carrotables/detach', $data);

    $response->assertStatus(200)
        ->assertJsonPath('message', 'Carrot detached successfully');

    $this->model->refresh();
    expect($this->model->carrots)->toHaveCount(0);
});

it('can sync carrots for model', function () {
    $carrot2 = Carrot::factory()->create();
    $carrot3 = Carrot::factory()->create();

    $data = [
        'model_type' => TestModel::class,
        'model_id' => $this->model->id,
        'carrot_ids' => [$carrot2->id, $carrot3->id],
        'role' => 'favorite'
    ];

    $response = $this->postJson('/api/carrotables/sync', $data);

    $response->assertStatus(200)
        ->assertJsonPath('message', 'Carrots synced successfully');

    $this->model->refresh();
    expect($this->model->carrots)->toHaveCount(2);
});

it('can get carrots by role', function () {
    $this->model->attachCarrot($this->carrot, 'favorite');

    $query = http_build_query([
        'model_type' => TestModel::class,
        'model_id' => $this->model->id,
        'role' => 'favorite'
    ]);

    $response = $this->getJson("/api/carrotables/carrots?{$query}");

    $response->assertStatus(200);
    $data = $response->json('data');
    expect($data)->toHaveCount(1)
        ->and($data[0]['id'])->toBe($this->carrot->id);
});

it('can get all carrots for model', function () {
    $carrot2 = Carrot::factory()->create();

    $this->model->attachCarrot($this->carrot, 'favorite');
    $this->model->attachCarrot($carrot2, 'backup');

    $query = http_build_query([
        'model_type' => TestModel::class,
        'model_id' => $this->model->id
    ]);

    $response = $this->getJson("/api/carrotables/carrots/all?{$query}");

    $response->assertStatus(200);
    $data = $response->json('data');
    expect($data)->toHaveCount(2);
});

it('can get roles for model', function () {
    $carrot2 = Carrot::factory()->create();

    $this->model->attachCarrot($this->carrot, 'favorite');
    $this->model->attachCarrot($carrot2, 'backup');

    $query = http_build_query([
        'model_type' => TestModel::class,
        'model_id' => $this->model->id
    ]);

    $response = $this->getJson("/api/carrotables/roles?{$query}");

    $response->assertStatus(200);
    $data = $response->json('data');
    expect($data)->toHaveCount(2)
        ->and($data)->toContain('favorite')
        ->and($data)->toContain('backup');
});