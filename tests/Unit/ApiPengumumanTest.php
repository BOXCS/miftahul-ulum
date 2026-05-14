<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('pengumuman endpoint return status 200', function () {
    $response = $this->getJson('/api/pengumuman');

    $response->assertStatus(200);
});

test('pengumuman return format array', function () {
    $response = $this->getJson('/api/pengumuman');

    $response->assertStatus(200);
    expect($response->json())->toBeArray();
});