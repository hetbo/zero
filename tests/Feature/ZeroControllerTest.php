<?php

it('can access zero index route', function () {
    $response = $this->get('/zero');

    $response->assertStatus(200)
        ->assertJson(['message' => 'Zero index']);
});

it('can post to zero route', function () {
    $response = $this->post('/zero', ['test' => 'data']);

    $response->assertStatus(200)
        ->assertJson(['data' => ['test' => 'data']]);
});