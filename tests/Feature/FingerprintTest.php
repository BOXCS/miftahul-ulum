<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Student;

class FingerprintTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_save_fingerprint_template(): void
    {
        $student = Student::create([
            "name" => "Test Student",
            "nis" => "12345678",
            "gender" => "Laki-laki",
            "tanggal_lahir" => "2010-01-01",
            "class" => "7A",
            "tahun_angkatan" => "2023",
            "address" => "Test Address",
            "status" => "aktif",
        ]);

        $response = $this->postJson("/students/{$student->id}/fingerprint", [
            "fingerprint_template" => "base64_encoded_template_data_here",
            "fingerprint_quality" => 95,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            "success" => true,
            "message" => "Data sidik jari berhasil disimpan.",
        ]);

        $this->assertDatabaseHas("students", [
            "id" => $student->id,
            "fingerprint_quality" => 95,
        ]);

        $student->refresh();
        $this->assertNotNull($student->scanned_at);
        $this->assertNotNull($student->fingerprint_template);
        $this->assertEquals(
            "base64_encoded_template_data_here",
            decrypt($student->fingerprint_template),
        );
    }

    public function test_validation_fails_for_invalid_quality(): void
    {
        $student = Student::create([
            "name" => "Test Student",
            "nis" => "12345679",
            "gender" => "Laki-laki",
            "tanggal_lahir" => "2010-01-01",
            "class" => "7A",
            "tahun_angkatan" => "2023",
            "address" => "Test Address",
            "status" => "aktif",
        ]);

        $response = $this->postJson("/students/{$student->id}/fingerprint", [
            "fingerprint_template" => "base64_encoded_template_data_here",
            "fingerprint_quality" => 150, // Max 100
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(["fingerprint_quality"]);
    }

    public function test_can_verify_fingerprint_for_attendance(): void
    {
        $student = Student::create([
            "name" => "Verify Student",
            "nis" => "98765432",
            "gender" => "Perempuan",
            "tanggal_lahir" => "2010-02-02",
            "class" => "8A",
            "tahun_angkatan" => "2023",
            "address" => "Verify Address",
            "status" => "aktif",
            "fingerprint_template" => encrypt("valid_base64_template"),
            "fingerprint_quality" => 90,
        ]);

        $response = $this->postJson("/attendance/verify", [
            "fingerprint_template" => "valid_base64_template",
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            "success" => true,
            "message" => "Verifikasi berhasil.",
            "student" => [
                "id" => $student->id,
                "name" => $student->name,
                "nis" => $student->nis,
                "class" => $student->class,
            ],
        ]);
    }

    public function test_verification_fails_for_unknown_fingerprint(): void
    {
        $response = $this->postJson("/attendance/verify", [
            "fingerprint_template" => "unknown_template",
        ]);

        $response->assertStatus(404);
        $response->assertJson([
            "success" => false,
            "message" => "Sidik jari tidak dikenali.",
        ]);
    }
}
