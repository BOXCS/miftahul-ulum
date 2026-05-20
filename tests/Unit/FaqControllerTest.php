<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Faq;

uses(RefreshDatabase::class);

/* ---------- INDEX (VIEW) ---------- */

test('halaman faqs dapat diakses oleh user login', function () {
    $this->withoutVite();
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get('/faqs');
    $response->assertStatus(200);
});

test('halaman faqs create dapat diakses oleh user login', function () {
    $this->withoutVite();
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get('/faqs/create');
    $response->assertStatus(200);
});

/* ---------- UNAUTHORIZED ---------- */

test('halaman faqs redirect ke login jika belum login', function () {
    $response = $this->get('/faqs');
    $response->assertRedirect('/login');
});

test('POST faqs redirect ke login jika belum login', function () {
    $response = $this->post('/faqs', []);
    $response->assertRedirect('/login');
});

/* ---------- STORE ---------- */

test('faq berhasil ditambahkan', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/faqs', [
        'pertanyaan' => 'Bagaimana cara mendaftar?',
        'jawaban'    => 'Silakan datang ke kantor sekretariat membawa berkas.',
        'kategori'   => 'Pendaftaran',
        'urutan'     => 1,
        'is_active'  => 1,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('faqs', [
        'pertanyaan' => 'Bagaimana cara mendaftar?',
        'kategori'   => 'Pendaftaran',
    ]);
});

test('faq gagal ditambahkan tanpa pertanyaan', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/faqs', [
        'jawaban'  => 'Jawaban tanpa pertanyaan',
        'kategori' => 'Umum',
    ]);

    $response->assertSessionHasErrors('pertanyaan');
});

test('faq gagal ditambahkan tanpa kategori', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/faqs', [
        'pertanyaan' => 'Pertanyaan tanpa kategori?',
        'jawaban'    => 'Jawaban-nya ini.',
    ]);

    $response->assertSessionHasErrors('kategori');
});

test('faq shift urutan jika konflik', function () {
    $user = User::factory()->create();

    Faq::create([
        'pertanyaan' => 'FAQ Lama',
        'jawaban'    => 'isi lama',
        'kategori'   => 'Umum',
        'urutan'     => 1,
        'is_active'  => true,
    ]);

    $this->actingAs($user)->post('/faqs', [
        'pertanyaan' => 'FAQ Baru di posisi 1',
        'jawaban'    => 'isi baru',
        'kategori'   => 'Umum',
        'urutan'     => 1,
        'is_active'  => 1,
    ]);

    // Yang lama harus di-shift ke urutan 2
    $this->assertDatabaseHas('faqs', [
        'pertanyaan' => 'FAQ Lama',
        'urutan'     => 2,
    ]);
    $this->assertDatabaseHas('faqs', [
        'pertanyaan' => 'FAQ Baru di posisi 1',
        'urutan'     => 1,
    ]);
});

/* ---------- UPDATE ---------- */

test('faq berhasil diupdate', function () {
    $user = User::factory()->create();
    $faq  = Faq::create([
        'pertanyaan' => 'Pertanyaan Lama',
        'jawaban'    => 'Jawaban lama',
        'kategori'   => 'Umum',
        'urutan'     => 5,
        'is_active'  => true,
    ]);

    $response = $this->actingAs($user)->put("/faqs/{$faq->id}", [
        'pertanyaan' => 'Pertanyaan Baru',
        'jawaban'    => 'Jawaban baru',
        'kategori'   => 'Pendaftaran',
        'urutan'     => 5,
        'is_active'  => 1,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('faqs', [
        'id'         => $faq->id,
        'pertanyaan' => 'Pertanyaan Baru',
        'kategori'   => 'Pendaftaran',
    ]);
});

/* ---------- DESTROY ---------- */

test('faq berhasil dihapus', function () {
    $user = User::factory()->create();
    $faq  = Faq::create([
        'pertanyaan' => 'FAQ akan dihapus?',
        'jawaban'    => 'iya',
        'kategori'   => 'Umum',
        'urutan'     => 10,
        'is_active'  => true,
    ]);

    $response = $this->actingAs($user)->delete("/faqs/{$faq->id}");

    $response->assertRedirect();
    $this->assertDatabaseMissing('faqs', ['id' => $faq->id]);
});
