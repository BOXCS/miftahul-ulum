<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

uses(RefreshDatabase::class);

/* ---------- INDEX (VIEW) ---------- */

test('halaman profil dapat diakses oleh user login', function () {
    $this->withoutVite();
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get('/profile');
    $response->assertStatus(200);
});

test('halaman profil redirect ke login jika belum login', function () {
    $response = $this->get('/profile');
    $response->assertRedirect('/login');
});

test('halaman settings dapat diakses oleh user login', function () {
    $this->withoutVite();
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get('/profile/settings');
    $response->assertStatus(200);
});

/* ---------- UPDATE PROFIL ---------- */

test('profil berhasil diupdate', function () {
    $user = User::factory()->create([
        'name'  => 'Nama Lama',
        'email' => 'lama@test.com',
    ]);

    $response = $this->actingAs($user)->put("/profile/{$user->id}", [
        'name'  => 'Nama Baru',
        'email' => 'baru@test.com',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('users', [
        'id'    => $user->id,
        'name'  => 'Nama Baru',
        'email' => 'baru@test.com',
    ]);
});

test('profil gagal diupdate tanpa nama', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->put("/profile/{$user->id}", [
        'email' => 'test@test.com',
    ]);

    $response->assertSessionHasErrors('name');
});

test('profil gagal diupdate jika bukan user sendiri', function () {
    $user  = User::factory()->create();
    $other = User::factory()->create();

    $response = $this->actingAs($user)->put("/profile/{$other->id}", [
        'name'  => 'Hacker',
        'email' => 'hacker@test.com',
    ]);

    $response->assertStatus(403);
});

/* ---------- UPDATE PASSWORD ---------- */

test('password berhasil diubah', function () {
    $user = User::factory()->create([
        'password' => bcrypt('oldpassword'),
    ]);

    $response = $this->actingAs($user)->patch("/profile/{$user->id}/password", [
        'current_password'      => 'oldpassword',
        'password'              => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $response->assertRedirect();
});

test('password gagal diubah jika current password salah', function () {
    $user = User::factory()->create([
        'password' => bcrypt('oldpassword'),
    ]);

    $response = $this->actingAs($user)->patch("/profile/{$user->id}/password", [
        'current_password'      => 'passwordsalah',
        'password'              => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $response->assertSessionHasErrors('current_password');
});

test('password gagal diubah jika konfirmasi tidak cocok', function () {
    $user = User::factory()->create([
        'password' => bcrypt('oldpassword'),
    ]);

    $response = $this->actingAs($user)->patch("/profile/{$user->id}/password", [
        'current_password'      => 'oldpassword',
        'password'              => 'newpassword123',
        'password_confirmation' => 'berbeda',
    ]);

    $response->assertSessionHasErrors('password');
});
