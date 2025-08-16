<?php

use Hetbo\Zero\Models\Carrot;

beforeEach(function () {
    $this->artisan('migrate');
});

it('can list carrots', function () {
    Carrot::factory(3)->create();

    $response = $this->getJson('/api/carrots');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'current_page',
            'data' => [
                '*' => ['id', 'name', 'length', 'created_at', 'updated_at']
            ]
        ]);
});

it('can create carrot', function () {
    $data = [
        'name' => 'Test Carrot',
        'length' => 15
    ];

    $response = $this->postJson('/api/carrots', $data);

    $response->assertStatus(201)
        ->assertJsonPath('data.name', 'Test Carrot')
        ->assertJsonPath('data.length', 15);

    $this->assertDatabaseHas('carrots', $data);
});

it('validates carrot creation', function () {
    $response = $this->postJson('/api/carrots', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'length']);
});

it('can show carrot', function () {
    $carrot = Carrot::factory()->create();

    $response = $this->getJson("/api/carrots/{$carrot->id}");

    $response->assertStatus(200)
        ->assertJsonPath('data.name', $carrot->name)
        ->assertJsonPath('data.length', $carrot->length);
});

it('returns 404 for non-existent carrot', function () {
    $response = $this->getJson('/api/carrots/999');

    $response->assertStatus(404);
});

it('can update carrot', function () {
    $carrot = Carrot::factory()->create();
    $data = [
        'name' => 'Updated Carrot',
        'length' => 20
    ];

    $response = $this->putJson("/api/carrots/{$carrot->id}", $data);

    $response->assertStatus(200)
        ->assertJsonPath('data.name', 'Updated Carrot')
        ->assertJsonPath('data.length', 20);

    $this->assertDatabaseHas('carrots', array_merge(['id' => $carrot->id], $data));
});

it('can delete carrot', function () {
    $carrot = Carrot::factory()->create();

    $response = $this->deleteJson("/api/carrots/{$carrot->id}");

    $response->assertStatus(200)
        ->assertJsonPath('message', 'Carrot deleted successfully');

    $this->assertDatabaseMissing('carrots', ['id' => $carrot->id]);
});

it('can search carrots', function () {
    Carrot::factory()->create(['name' => 'Big Carrot']);
    Carrot::factory()->create(['name' => 'Small Carrot']);
    Carrot::factory()->create(['name' => 'Tiny Radish']);

    $response = $this->getJson('/api/carrots?search=Carrot');

    $response->assertStatus(200);
    $data = $response->json('data');
    expect($data)->toHaveCount(2);
});

it('can search carrots by name', function () {
    Carrot::factory()->create(['name' => 'Orange Carrot']);
    Carrot::factory()->create(['name' => 'Purple Carrot']);
    Carrot::factory()->create(['name' => 'Red Radish']);

    $response = $this->getJson('/api/carrots/search/name?name=Carrot');

    $response->assertStatus(200);
    $data = $response->json('data');
    expect($data)->toHaveCount(2);
});

it('can search carrots by length range', function () {
    Carrot::factory()->create(['length' => 5]);
    Carrot::factory()->create(['length' => 15]);
    Carrot::factory()->create(['length' => 25]);

    $response = $this->getJson('/api/carrots/search/length?min_length=10&max_length=20');

    $response->assertStatus(200);
    $data = $response->json('data');
    expect($data)->toHaveCount(1)
        ->and($data[0]['length'])->toBe(15);
});