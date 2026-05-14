<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('faq endpoint return status 200', function () {
    $response = $this->getJson('/api/faq');

    $response->assertStatus(200);
});

test('faq detail return 404 jika tidak ada', function () {
    $response = $this->getJson('/api/faq/99999');

    $response->assertStatus(404);
});