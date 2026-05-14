<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use App\Models\ParentModel;

uses(RefreshDatabase::class);

test('login berhasil dengan kredensial benar', function () {
    ParentModel::create([
        'name'     => 'Test Parent',
        'email'    => 'test@example.com',
        'password' => Hash::make('password123'),
        'phone'    => '08123456789',
        'role'     => 'ortu',
    ]);

    $response = $this->postJson('/api/login', [
        'email'    => 'test@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(200)
             ->assertJson(['success' => true]);
});

test('login gagal dengan kredensial salah', function () {
    ParentModel::create([
        'name'     => 'Test Parent',
        'email'    => 'test@example.com',
        'password' => Hash::make('password123'),
        'phone'    => '08123456789',
        'role'     => 'ortu',
    ]);

    $response = $this->postJson('/api/login', [
        'email'    => 'test@example.com',
        'password' => 'salah_password',
    ]);

    $response->assertStatus(401)
             ->assertJson(['success' => false]);
});

test('login gagal tanpa email', function () {
    $response = $this->postJson('/api/login', [
        'password' => 'password123',
    ]);

    $response->assertStatus(422);
});