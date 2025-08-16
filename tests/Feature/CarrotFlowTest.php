<?php

namespace Hetbo\Zero\Tests\Feature;

use Hetbo\Zero\Models\Carrot;
use Hetbo\Zero\Tests\TestUser;
use Illuminate\Auth\AuthenticationException;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

test('guests are blocked from the carrots page', function () {
    $this->withoutExceptionHandling();
    $this->expectException(AuthenticationException::class);
    $this->get('/carrots');
});


test('an authenticated user can view their carrots', function () {
    $user = TestUser::factory()->create();
    Carrot::factory()->create(['user_id' => $user->id, 'name' => 'My First Carrot']);

    $this->actingAs($user)
        ->get('/carrots')
        ->assertOk()
        ->assertSeeText('My First Carrot');
});

test('a user cannot see another users carrots', function () {
    // Arrange
    $userOne = TestUser::factory()->create();
    $userTwo = TestUser::factory()->create();
    Carrot::create(['user_id' => $userTwo->id, 'name' => 'User Two Carrot', 'length' => 20]);

    // Act & Assert
    actingAs($userOne)
        ->get('/carrots')
        ->assertOk()
        ->assertDontSeeText('User Two Carrot');
});

test('a user can add a new carrot', function () {
    // Arrange
    $user = TestUser::factory()->create();

    // Act
    actingAs($user)->post('/carrots', [
        'name' => 'A Brand New Carrot',
        'length' => 25,
    ]);

    // Assert
    assertDatabaseHas('carrots', [
        'user_id' => $user->id,
        'name' => 'A Brand New Carrot',
        'length' => 25,
    ]);
});

test('validation fails if name or length is missing', function ($data) {
    $user = TestUser::factory()->create();

    actingAs($user)
        ->post('/carrots', $data)
        ->assertSessionHasErrors();
})->with([
    'missing name' => [['length' => 10]],
    'missing length' => [['name' => 'Carrot']],
]);

test('a user can delete their own carrot', function () {
    // Arrange
    $user = TestUser::factory()->create();
    $carrot = Carrot::create(['user_id' => $user->id, 'name' => 'Carrot To Be Deleted', 'length' => 5]);

    // Act
    actingAs($user)->delete('/carrots/' . $carrot->id);

    // Assert
    assertDatabaseMissing('carrots', ['id' => $carrot->id]);
});

test('a user cannot delete another users carrot', function () {
    // Arrange
    $userOne = TestUser::factory()->create();
    $userTwo = TestUser::factory()->create();
    $carrotOfUserTwo = Carrot::create(['user_id' => $userTwo->id, 'name' => 'Secret Carrot', 'length' => 50]);

    // Act & Assert
    actingAs($userOne)
        ->delete('/carrots/' . $carrotOfUserTwo->id)
        ->assertForbidden(); // Expect a 403 response

    assertDatabaseHas('carrots', ['id' => $carrotOfUserTwo->id]);
});