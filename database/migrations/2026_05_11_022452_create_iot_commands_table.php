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
        Schema::create('iot_commands', function (Blueprint $table) {
            $table->id();
            // Tipe perintah: 'enroll' (registrasi sidik jari baru) atau 'scan_request' (info)
            $table->string('type', 50);
            // Target student saat enroll
            $table->foreignId('student_id')->nullable()->constrained('students')->onDelete('set null');
            // Fingerprint ID yang dialokasikan untuk slot template baru di sensor
            $table->unsignedSmallInteger('fingerprint_id')->nullable();
            // Status command lifecycle
            $table->enum('status', ['pending', 'in_progress', 'done', 'failed', 'expired'])
                  ->default('pending');
            // Hasil dari ESP32 (JSON: success, message, quality, dst)
            $table->json('result')->nullable();
            // Kapan ESP32 mulai eksekusi
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iot_commands');
    }
};
