<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Student;
use App\Models\ParentModel;

uses(RefreshDatabase::class);

test('halaman santri dapat diakses oleh user login', function () {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get('/students');
    $response->assertStatus(200);
});

test('halaman santri redirect ke login jika belum login', function () {
    $response = $this->get('/students');
    $response->assertRedirect('/login');
});

test('halaman tambah santri dapat diakses', function () {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get('/students/create');
    $response->assertStatus(200);
});

test('santri berhasil ditambahkan', function () {
    $user   = User::factory()->create();
    $parent = ParentModel::create([
        'name'  => 'Ortu Test',
        'email' => 'ortu@test.com',
        'password' => bcrypt('password'),
        'phone' => '08123456789',
        'role'  => 'ortu',
    ]);

    $response = $this->actingAs($user)->post('/students', [
        'name'           => 'Ahmad Test',
        'parent_id'      => $parent->id,
        'nis'            => '12345678',
        'gender'         => 'Laki-laki',
        'tanggal_lahir'  => '2010-01-01',
        'class'          => '7A',
        'tahun_angkatan' => '2024',
        'address'        => 'Jl. Test No. 1',
        'status'         => 'aktif',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('students', ['nis' => '12345678']);
});

test('santri gagal ditambahkan tanpa nama', function () {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->post('/students', [
        'nis'    => '12345678',
        'gender' => 'Laki-laki',
    ]);
    $response->assertSessionHasErrors('name');
});

test('santri gagal ditambahkan dengan NIS duplikat', function () {
    $user   = User::factory()->create();
    $parent = ParentModel::create([
        'name'     => 'Ortu Test',
        'email'    => 'ortu@test.com',
        'password' => bcrypt('password'),
        'phone'    => '08123456789',
        'role'     => 'ortu',
    ]);

    // Buat santri pertama
    $this->actingAs($user)->post('/students', [
        'name'           => 'Ahmad Test',
        'parent_id'      => $parent->id,
        'nis'            => '99999999',
        'gender'         => 'Laki-laki',
        'tanggal_lahir'  => '2010-01-01',
        'class'          => '7A',
        'tahun_angkatan' => '2024',
        'address'        => 'Jl. Test No. 1',
        'status'         => 'aktif',
    ]);

    // Buat santri kedua dengan NIS sama
    $response = $this->actingAs($user)->post('/students', [
        'name'           => 'Budi Test',
        'parent_id'      => $parent->id,
        'nis'            => '99999999',
        'gender'         => 'Laki-laki',
        'tanggal_lahir'  => '2010-01-01',
        'class'          => '7B',
        'tahun_angkatan' => '2024',
        'address'        => 'Jl. Test No. 2',
        'status'         => 'aktif',
    ]);

    $response->assertSessionHasErrors('nis');
});

test('santri berhasil dihapus', function () {
    $user   = User::factory()->create();
    $parent = ParentModel::create([
        'name'     => 'Ortu Test',
        'email'    => 'ortu@test.com',
        'password' => bcrypt('password'),
        'phone'    => '08123456789',
        'role'     => 'ortu',
    ]);

    $student = Student::create([
        'name'           => 'Ahmad Test',
        'parent_id'      => $parent->id,
        'nis'            => '11111111',
        'gender'         => 'Laki-laki',
        'tanggal_lahir'  => '2010-01-01',
        'class'          => '7A',
        'tahun_angkatan' => '2024',
        'address'        => 'Jl. Test No. 1',
        'status'         => 'aktif',
    ]);

    $response = $this->actingAs($user)->delete("/students/{$student->id}");
    $response->assertRedirect();
    $this->assertDatabaseMissing('students', ['id' => $student->id]);
});