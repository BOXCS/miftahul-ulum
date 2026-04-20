<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("attendance", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("student_id")
                ->constrained("students")
                ->onDelete("cascade");
            $table->date("tanggal");
            $table->timestamp("jam_masuk")->nullable();
            $table->timestamp("jam_keluar")->nullable();
            $table->enum("waktu_shalat", [
                "Subuh",
                "Dzuhur",
                "Ashar",
                "Maghrib",
                "Isya",
            ]);
            $table
                ->enum("status", [
                    "hadir",
                    "terlambat",
                    "izin",
                    "sakit",
                    "alpha",
                ])
                ->default("alpha");
            $table->text("keterangan")->nullable();
            $table->timestamps();

            $table->index(["student_id", "tanggal"]);
            $table->index("waktu_shalat");
            $table->unique(["student_id", "tanggal", "waktu_shalat"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("attendance");
    }
};
