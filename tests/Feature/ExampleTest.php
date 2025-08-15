<?php

it('returns 404 for undefined routes', function () {
    $response = $this->get('/');
    $response->assertStatus(404);
});