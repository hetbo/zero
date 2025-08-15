<?php

it('renders index view correctly', function () {
    $response = $this->get('/zero');

    $response->assertStatus(200)
        ->assertSee('Zero Package')
        ->assertSee('Welcome to Zero package!');
});

it('renders show view with parameters', function () {
    $response = $this->get('/zero/123');

    $response->assertStatus(200)
        ->assertSee('Zero Item: 123')
        ->assertSee('Data: Sample data');
});