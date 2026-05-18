<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

uses(RefreshDatabase::class);

test('dashboard dapat diakses oleh user login', function () {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get('/');
    $response->assertStatus(200);
});

test('dashboard redirect ke login jika belum login', function () {
    $response = $this->get('/');
    $response->assertRedirect('/login');
});