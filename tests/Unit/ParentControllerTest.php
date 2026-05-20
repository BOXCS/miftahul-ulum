<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\ParentModel;

uses(RefreshDatabase::class);

/* ---------- INDEX (VIEW) ---------- */

test('halaman parents dapat diakses oleh user login', function () {
    $this->withoutVite();
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get('/parents');
    $response->assertStatus(200);
});

test('halaman parents create dapat diakses oleh user login', function () {
    $this->withoutVite();
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get('/parents/create');
    $response->assertStatus(200);
});

/* ---------- UNAUTHORIZED ---------- */

test('halaman parents redirect ke login jika belum login', function () {
    $response = $this->get('/parents');
    $response->assertRedirect('/login');
});

test('POST parents redirect ke login jika belum login', function () {
    $response = $this->post('/parents', []);
    $response->assertRedirect('/login');
});

/* ---------- STORE ---------- */

test('wali santri berhasil ditambahkan', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/parents', [
        'name'                  => 'Pak Budi',
        'relationship'          => 'ayah',
        'phone'                 => '08123456789',
        'email'                 => 'pak.budi@test.com',
        'address'               => 'Jl. Mawar No. 1',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('parents', [
        'name'  => 'Pak Budi',
        'email' => 'pak.budi@test.com',
        'role'  => 'ortu',
    ]);
});

test('wali gagal ditambahkan tanpa nama', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/parents', [
        'relationship'          => 'ayah',
        'phone'                 => '08123456789',
        'address'               => 'Jl. Mawar',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertSessionHasErrors('name');
});

test('wali gagal ditambahkan dengan relationship tidak valid', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/parents', [
        'name'                  => 'Pak Budi',
        'relationship'          => 'paman', // bukan enum
        'phone'                 => '08123456789',
        'address'               => 'Jl. Mawar',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertSessionHasErrors('relationship');
});

test('wali gagal ditambahkan jika password tidak match', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/parents', [
        'name'                  => 'Pak Budi',
        'relationship'          => 'ayah',
        'phone'                 => '08123456789',
        'address'               => 'Jl. Mawar',
        'password'              => 'password123',
        'password_confirmation' => 'salah',
    ]);

    $response->assertSessionHasErrors('password');
});

/* ---------- UPDATE ---------- */

test('wali berhasil diupdate (tanpa ganti password)', function () {
    $user   = User::factory()->create();
    $parent = ParentModel::create([
        'name'         => 'Pak Lama',
        'email'        => 'lama@test.com',
        'password'     => bcrypt('oldpass'),
        'phone'        => '0811',
        'address'      => 'Jl. Lama',
        'relationship' => 'ayah',
        'role'         => 'ortu',
    ]);

    $response = $this->actingAs($user)->put("/parents/{$parent->id}", [
        'name'         => 'Pak Baru',
        'relationship' => 'ayah',
        'phone'        => '0822',
        'email'        => 'baru@test.com',
        'address'      => 'Jl. Baru',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('parents', [
        'id'    => $parent->id,
        'name'  => 'Pak Baru',
        'email' => 'baru@test.com',
    ]);
});

/* ---------- DESTROY ---------- */

test('wali berhasil dihapus', function () {
    $user   = User::factory()->create();
    $parent = ParentModel::create([
        'name'         => 'Pak Hapus',
        'email'        => 'hapus@test.com',
        'password'     => bcrypt('password'),
        'phone'        => '08123',
        'address'      => 'Jl. Hapus',
        'relationship' => 'ayah',
        'role'         => 'ortu',
    ]);

    $response = $this->actingAs($user)->delete("/parents/{$parent->id}");

    $response->assertRedirect();
    $this->assertDatabaseMissing('parents', ['id' => $parent->id]);
});
