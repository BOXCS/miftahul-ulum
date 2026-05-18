<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

uses(RefreshDatabase::class);

test('halaman login dapat diakses', function () {
    $response = $this->get('/login');
    $response->assertStatus(200);
});

test('login berhasil redirect ke dashboard', function () {
    $user = User::factory()->create([
        'email'    => 'admin@test.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->post('/login', [
        'email'    => 'admin@test.com',
        'password' => 'password123',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticatedAs($user);
});

test('login gagal dengan password salah', function () {
    User::factory()->create([
        'email'    => 'admin@test.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->post('/login', [
        'email'    => 'admin@test.com',
        'password' => 'salah',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('login gagal tanpa email', function () {
    $response = $this->post('/login', [
        'password' => 'password123',
    ]);

    $response->assertSessionHasErrors('email');
});

test('login gagal dengan password kurang dari 6 karakter', function () {
    $response = $this->post('/login', [
        'email'    => 'admin@test.com',
        'password' => '123',
    ]);

    $response->assertSessionHasErrors('password');
});

test('logout berhasil redirect ke login', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post('/logout');

    $response->assertRedirect('/login');
    $this->assertGuest();
});