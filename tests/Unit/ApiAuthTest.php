<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('login berhasil dengan kredensial benar', function () {
    $user = \App\Models\User::factory()->create([
        'email'    => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email'    => 'test@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(200);
});

test('login gagal dengan kredensial salah', function () {
    \App\Models\User::factory()->create([
        'email'    => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email'    => 'test@example.com',
        'password' => 'salah_password',
    ]);

    $response->assertStatus(401);
});

test('login gagal tanpa email', function () {
    $response = $this->postJson('/api/login', [
        'password' => 'password123',
    ]);

    $response->assertStatus(422);
});