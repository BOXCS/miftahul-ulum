<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

uses(RefreshDatabase::class);

/* ---------- INDEX (VIEW) ---------- */

test('halaman staff dapat diakses oleh user login', function () {
    $this->withoutVite();
    $user = User::factory()->create(['role' => 'superadmin']);
    $response = $this->actingAs($user)->get('/staff');
    $response->assertStatus(200);
});

test('halaman staff redirect ke login jika belum login', function () {
    $response = $this->get('/staff');
    $response->assertRedirect('/login');
});

/* ---------- STORE ---------- */

test('staff berhasil ditambahkan oleh superadmin', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin']);

    $response = $this->actingAs($superadmin)->post('/staff', [
        'name'                  => 'Staff Baru',
        'email'                 => 'staffbaru@test.com',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
        'role'                  => 'admin',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('users', [
        'email' => 'staffbaru@test.com',
        'role'  => 'admin',
    ]);
});

test('staff gagal ditambahkan oleh bukan superadmin', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->post('/staff', [
        'name'                  => 'Staff Baru',
        'email'                 => 'staffbaru@test.com',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
        'role'                  => 'admin',
    ]);

    $response->assertStatus(403);
});

test('staff gagal ditambahkan dengan email duplikat', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin']);
    User::factory()->create(['email' => 'duplikat@test.com']);

    $response = $this->actingAs($superadmin)->post('/staff', [
        'name'                  => 'Staff Duplikat',
        'email'                 => 'duplikat@test.com',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
        'role'                  => 'admin',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('error');
});

/* ---------- UPDATE ---------- */

test('staff berhasil diupdate oleh superadmin', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin']);
    $staff      = User::factory()->create([
        'name'  => 'Staff Lama',
        'email' => 'stafflama@test.com',
        'role'  => 'admin',
    ]);

    $response = $this->actingAs($superadmin)->put("/staff/{$staff->id}", [
        'name'                  => 'Staff Baru',
        'email'                 => 'staffbaru2@test.com',
        'role'                  => 'admin',
        'password'              => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('users', [
        'id'    => $staff->id,
        'name'  => 'Staff Baru',
        'email' => 'staffbaru2@test.com',
    ]);
});

test('superadmin tidak bisa update data diri sendiri via staff', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin']);

    $response = $this->actingAs($superadmin)->put("/staff/{$superadmin->id}", [
        'name'                  => 'Coba Update',
        'email'                 => 'update@test.com',
        'role'                  => 'superadmin',
        'password'              => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('error');
});

/* ---------- DESTROY ---------- */

test('staff berhasil dihapus oleh superadmin', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin']);
    $staff      = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($superadmin)->delete("/staff/{$staff->id}");

    $response->assertRedirect();
    $this->assertDatabaseMissing('users', ['id' => $staff->id]);
});

test('superadmin tidak bisa hapus diri sendiri', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin']);

    $response = $this->actingAs($superadmin)->delete("/staff/{$superadmin->id}");

    $response->assertRedirect();
    $response->assertSessionHas('error');
    $this->assertDatabaseHas('users', ['id' => $superadmin->id]);
});

test('staff gagal dihapus oleh bukan superadmin', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $other = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->delete("/staff/{$other->id}");

    $response->assertStatus(403);
});
