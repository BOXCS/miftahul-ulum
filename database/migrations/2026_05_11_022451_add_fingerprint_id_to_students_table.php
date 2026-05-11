<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Idempotent — skip jika kolom sudah ada
        if (!Schema::hasColumn('students', 'fingerprint_id')) {
            Schema::table('students', function (Blueprint $table) {
                // ID template fingerprint di sensor ESP32 (1..N).
                // Server hanya simpan mapping: fingerprint_id → student_id.
                $table->unsignedSmallInteger('fingerprint_id')->nullable()->after('id');
            });
        }
        // Pastikan unique constraint ada
        try {
            Schema::table('students', function (Blueprint $table) {
                $table->unique('fingerprint_id', 'students_fingerprint_id_unique');
            });
        } catch (\Throwable $e) {
            // Index sudah ada, abaikan
        }
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            try { $table->dropUnique('students_fingerprint_id_unique'); } catch (\Throwable $e) {}
            try { $table->dropColumn('fingerprint_id'); } catch (\Throwable $e) {}
        });
    }
};
