<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Attendance;
use App\Models\ChatMessage;
use App\Models\IotCommand;
use App\Models\ParentModel;
use App\Models\Permission;
use App\Models\Student;
use App\Models\User;

uses(RefreshDatabase::class);

/* ---------- Helper ---------- */

function makeParentAndStudent(string $nis = '77770001'): array
{
    $parent = ParentModel::create([
        'name'         => 'Parent Model Test',
        'email'        => 'parentmodel_' . $nis . '@test.com',
        'password'     => bcrypt('password'),
        'phone'        => '08100000001',
        'relationship' => 'ayah',
        'address'      => 'Jl. Model',
        'role'         => 'ortu',
    ]);

    $student = Student::create([
        'name'           => 'Student Model Test',
        'parent_id'      => $parent->id,
        'nis'            => $nis,
        'gender'         => 'Laki-laki',
        'tanggal_lahir'  => '2010-01-01',
        'class'          => '7A',
        'tahun_angkatan' => '2024',
        'address'        => 'Jl. Model',
        'status'         => 'aktif',
    ]);

    return [$parent, $student];
}

/* ---------- Student Model ---------- */

test('student memiliki relasi ke parent', function () {
    [, $student] = makeParentAndStudent('77770001');

    expect($student->parent)->toBeInstanceOf(ParentModel::class);
    expect($student->parent->id)->toBe($student->parent_id);
});

test('student memiliki relasi ke attendance', function () {
    [, $student] = makeParentAndStudent('77770002');

    $attendance = Attendance::create([
        'student_id'   => $student->id,
        'tanggal'      => now()->toDateString(),
        'waktu_shalat' => 'Subuh',
        'status'       => 'hadir',
    ]);

    expect($student->attendance)->toHaveCount(1);
    expect($student->attendance->first()->id)->toBe($attendance->id);
});

test('student memiliki relasi ke permissions', function () {
    [, $student] = makeParentAndStudent('77770003');

    $permission = Permission::create([
        'student_id'      => $student->id,
        'jenis'           => 'sakit',
        'tanggal_mulai'   => now()->toDateString(),
        'tanggal_selesai' => now()->addDay()->toDateString(),
        'status'          => 'pending',
    ]);

    expect($student->permissions)->toHaveCount(1);
    expect($student->permissions->first()->id)->toBe($permission->id);
});

/* ---------- ParentModel Model ---------- */

test('parentModel memiliki relasi ke students', function () {
    [$parent, $student] = makeParentAndStudent('77770004');

    expect($parent->students)->toHaveCount(1);
    expect($parent->students->first()->id)->toBe($student->id);
});

test('parentModel memiliki relasi ke messages', function () {
    [$parent] = makeParentAndStudent('77770005');
    $user     = User::factory()->create();

    ChatMessage::create([
        'parent_id' => $parent->id,
        'sender'    => 'admin',
        'pesan'     => 'Halo!',
    ]);

    expect($parent->messages)->toHaveCount(1);
});

/* ---------- Attendance Model ---------- */

test('attendance memiliki relasi ke student', function () {
    [, $student] = makeParentAndStudent('77770006');

    $attendance = Attendance::create([
        'student_id'   => $student->id,
        'tanggal'      => now()->toDateString(),
        'waktu_shalat' => 'Dzuhur',
        'status'       => 'hadir',
    ]);

    expect($attendance->student)->toBeInstanceOf(Student::class);
    expect($attendance->student->id)->toBe($student->id);
});

/* ---------- ChatMessage Model ---------- */

test('chatMessage memiliki relasi ke parent', function () {
    [$parent] = makeParentAndStudent('77770008');

    $message = ChatMessage::create([
        'parent_id' => $parent->id,
        'pesan'     => 'Test pesan relasi',
    ]);

    expect($message->parent)->toBeInstanceOf(ParentModel::class);
    expect($message->parent->id)->toBe($parent->id);
});

/* ---------- IotCommand Model ---------- */

test('iotCommand memiliki relasi ke student', function () {
    [, $student] = makeParentAndStudent('77770009');

    $command = IotCommand::create([
        'type'       => 'enroll',
        'student_id' => $student->id,
        'status'     => 'pending',
    ]);

    expect($command->student)->toBeInstanceOf(Student::class);
    expect($command->student->id)->toBe($student->id);
});

/* ---------- ParentModel user() ---------- */

test('parentModel memiliki relasi ke user', function () {
    $user   = User::factory()->create();
    $parent = ParentModel::create([
        'name'         => 'Parent With User',
        'email'        => 'parentwithuser@test.com',
        'password'     => bcrypt('password'),
        'phone'        => '08199999999',
        'relationship' => 'ibu',
        'address'      => 'Jl. User',
        'role'         => 'ortu',
        'user_id'      => $user->id,
    ]);

    expect($parent->user)->toBeInstanceOf(User::class);
    expect($parent->user->id)->toBe($user->id);
});

/* ---------- User Model Methods ---------- */

test('user isAdmin mengembalikan true untuk role admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    expect($admin->isAdmin())->toBeTrue();
});

test('user roleLabel mengembalikan label yang benar', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin']);
    $admin      = User::factory()->create(['role' => 'admin', 'email' => 'admin2@test.com']);

    expect($superadmin->roleLabel())->toBe('Super Admin');
    expect($admin->roleLabel())->toBe('Admin');

    // Test default case tanpa DB insert (hindari CHECK constraint)
    $other = new User(['role' => 'unknown']);
    expect($other->roleLabel())->toBe('Staff');
});

/* ---------- Permission Model ---------- */

test('permission memiliki relasi ke student', function () {
    [, $student] = makeParentAndStudent('77770007');

    $permission = Permission::create([
        'student_id'      => $student->id,
        'jenis'           => 'pulang',
        'tanggal_mulai'   => now()->toDateString(),
        'tanggal_selesai' => now()->addDay()->toDateString(),
        'status'          => 'pending',
    ]);

    expect($permission->student)->toBeInstanceOf(Student::class);
    expect($permission->student->id)->toBe($student->id);
});
