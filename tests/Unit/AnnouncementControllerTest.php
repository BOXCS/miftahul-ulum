<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Announcement;

uses(RefreshDatabase::class);

test('halaman pengumuman dapat diakses oleh user login', function () {
    $this->withoutVite();
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/announcements');

    $response->assertStatus(200);
});

test('halaman pengumuman redirect ke login jika belum login', function () {
    $response = $this->get('/announcements');

    $response->assertRedirect('/login');
});

test('pengumuman berhasil dibuat', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/announcements', [
        'judul'    => 'Test Pengumuman',
        'konten'   => 'Isi pengumuman test yang cukup panjang',
        'kategori' => 'umum',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('announcements', [
        'judul' => 'Test Pengumuman',
    ]);
});

test('pengumuman gagal dibuat tanpa judul', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/announcements', [
        'konten'   => 'Isi pengumuman',
        'kategori' => 'umum',
    ]);

    $response->assertSessionHasErrors('judul');
});

test('pengumuman gagal dibuat dengan kategori tidak valid', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/announcements', [
        'judul'    => 'Test',
        'konten'   => 'Isi pengumuman',
        'kategori' => 'invalid_kategori',
    ]);

    $response->assertSessionHasErrors('kategori');
});

test('pengumuman dapat dihapus', function () {
    $user = User::factory()->create();

    $announcement = Announcement::create([
        'judul'    => 'Pengumuman Hapus',
        'konten'   => 'Konten pengumuman yang akan dihapus',
        'kategori' => 'umum',
    ]);

    $response = $this->actingAs($user)->delete("/announcements/{$announcement->id}");

    $response->assertRedirect();
    $this->assertDatabaseMissing('announcements', ['id' => $announcement->id]);
});