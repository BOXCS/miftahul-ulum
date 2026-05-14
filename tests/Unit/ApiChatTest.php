<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('chat history return array', function () {
    $response = $this->getJson('/api/chat/1/history');

    $response->assertStatus(200);
    expect($response->json())->toBeArray();
});

test('send message tanpa pesan return error', function () {
    $response = $this->postJson('/api/chat/1/send-api', []);

    $response->assertStatus(422);
});