<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use App\Models\User;
use App\Models\Student;
use App\Models\ParentModel;
use App\Models\Permission;

uses(RefreshDatabase::class);

/**
 * Helper: bikin student aktif untuk pengajuan izin.
 */
function makeStudentForPermission(string $nis = '30240001'): Student
{
    $parent = ParentModel::create([
        'name'         => 'Ortu Perm',
        'email'        => 'ortu_perm_' . $nis . '@test.com',
        'password'     => bcrypt('password'),
        'phone'        => '08123456789',
        'address'      => 'Jl. Perm',
        'relationship' => 'ayah',
        'role'         => 'ortu',
    ]);

    return Student::create([
        'name'           => 'Santri Perm ' . $nis,
        'parent_id'      => $parent->id,
        'nis'            => $nis,
        'gender'         => 'Laki-laki',
        'tanggal_lahir'  => '2010-01-01',
        'class'          => '7A',
        'tahun_angkatan' => '2024',
        'address'        => 'Jl. Perm No. 1',
        'status'         => 'aktif',
    ]);
}

/* ---------- INDEX (VIEW) ---------- */

test('halaman permissions dapat diakses oleh user login', function () {
    $this->withoutVite();
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get('/permissions');
    $response->assertStatus(200);
});

test('halaman permissions create dapat diakses oleh user login', function () {
    $this->withoutVite();
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get('/permissions/create');
    $response->assertStatus(200);
});

/* ---------- UNAUTHORIZED ---------- */

test('halaman permissions redirect ke login jika belum login', function () {
    $response = $this->get('/permissions');
    $response->assertRedirect('/login');
});

test('POST permissions redirect ke login jika belum login', function () {
    $response = $this->post('/permissions', []);
    $response->assertRedirect('/login');
});

/* ---------- STORE ---------- */

test('izin berhasil dibuat', function () {
    $user    = User::factory()->create();
    $student = makeStudentForPermission('30240001');

    $response = $this->actingAs($user)->post('/permissions', [
        'student_id'      => $student->id,
        'jenis'           => 'sakit',
        'tanggal_mulai'   => '2026-05-19',
        'tanggal_selesai' => '2026-05-21',
        'keterangan'      => 'Demam tinggi',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('permissions', [
        'student_id' => $student->id,
        'jenis'      => 'sakit',
        'status'     => 'pending',
    ]);
});

test('izin gagal dibuat tanpa student_id', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/permissions', [
        'jenis'           => 'sakit',
        'tanggal_mulai'   => '2026-05-19',
        'tanggal_selesai' => '2026-05-21',
    ]);

    $response->assertSessionHasErrors('student_id');
});

test('izin gagal dibuat dengan jenis tidak valid', function () {
    $user    = User::factory()->create();
    $student = makeStudentForPermission('30240002');

    $response = $this->actingAs($user)->post('/permissions', [
        'student_id'      => $student->id,
        'jenis'           => 'libur_panjang', // bukan enum
        'tanggal_mulai'   => '2026-05-19',
        'tanggal_selesai' => '2026-05-21',
    ]);

    $response->assertSessionHasErrors('jenis');
});

test('izin gagal dibuat jika tanggal selesai sebelum tanggal mulai', function () {
    $user    = User::factory()->create();
    $student = makeStudentForPermission('30240003');

    $response = $this->actingAs($user)->post('/permissions', [
        'student_id'      => $student->id,
        'jenis'           => 'pulang',
        'tanggal_mulai'   => '2026-05-21',
        'tanggal_selesai' => '2026-05-19',
    ]);

    $response->assertSessionHasErrors('tanggal_selesai');
});

/* ---------- APPROVE ---------- */

test('izin berhasil disetujui', function () {
    Event::fake();

    $user    = User::factory()->create();
    $student = makeStudentForPermission('30240010');

    $permission = Permission::create([
        'student_id'      => $student->id,
        'jenis'           => 'sakit',
        'tanggal_mulai'   => '2026-05-19',
        'tanggal_selesai' => '2026-05-21',
        'keterangan'      => 'Demam',
        'status'          => 'pending',
    ]);

    $response = $this->actingAs($user)->post("/permissions/{$permission->id}/approve", [
        'approved_by' => 'Admin Test',
        'catatan'     => 'OK',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('permissions', [
        'id'          => $permission->id,
        'status'      => 'disetujui',
        'approved_by' => 'Admin Test',
    ]);
});

/* ---------- REJECT ---------- */

test('izin berhasil ditolak dengan catatan', function () {
    Event::fake();

    $user    = User::factory()->create();
    $student = makeStudentForPermission('30240011');

    $permission = Permission::create([
        'student_id'      => $student->id,
        'jenis'           => 'pulang',
        'tanggal_mulai'   => '2026-05-19',
        'tanggal_selesai' => '2026-05-20',
        'keterangan'      => 'Acara keluarga',
        'status'          => 'pending',
    ]);

    $response = $this->actingAs($user)->post("/permissions/{$permission->id}/reject", [
        'catatan'     => 'Alasan tidak mendesak',
        'approved_by' => 'Admin',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('permissions', [
        'id'      => $permission->id,
        'status'  => 'ditolak',
        'catatan' => 'Alasan tidak mendesak',
    ]);
});

test('izin gagal ditolak tanpa catatan', function () {
    Event::fake();

    $user    = User::factory()->create();
    $student = makeStudentForPermission('30240012');

    $permission = Permission::create([
        'student_id'      => $student->id,
        'jenis'           => 'pulang',
        'tanggal_mulai'   => '2026-05-19',
        'tanggal_selesai' => '2026-05-20',
        'keterangan'      => 'Acara keluarga',
        'status'          => 'pending',
    ]);

    $response = $this->actingAs($user)->post("/permissions/{$permission->id}/reject", [
        'approved_by' => 'Admin',
    ]);

    $response->assertSessionHasErrors('catatan');
});

/* ---------- UPDATE ---------- */

test('izin berhasil diupdate', function () {
    $user    = User::factory()->create();
    $student = makeStudentForPermission('30240020');

    $permission = Permission::create([
        'student_id'      => $student->id,
        'jenis'           => 'sakit',
        'tanggal_mulai'   => '2026-05-19',
        'tanggal_selesai' => '2026-05-21',
        'keterangan'      => 'Demam',
        'status'          => 'pending',
    ]);

    $response = $this->actingAs($user)->put("/permissions/{$permission->id}", [
        'student_id'      => $student->id,
        'jenis'           => 'kegiatan',
        'tanggal_mulai'   => '2026-05-22',
        'tanggal_selesai' => '2026-05-23',
        'keterangan'      => 'Lomba',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('permissions', [
        'id'    => $permission->id,
        'jenis' => 'kegiatan',
    ]);
});
