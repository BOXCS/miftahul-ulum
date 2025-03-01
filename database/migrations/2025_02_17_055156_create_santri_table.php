<?php

// database/migrations/xxxx_xx_xx_create_santri_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSantriTable extends Migration
{
    public function up()
    {
        Schema::create('santri', function (Blueprint $table) {
            $table->string('id', 10)->primary();  // Format: MU01(tahun angkatan 2 digit)(id auto increment 4 digit)
            $table->string('nama_lengkap', 100)->unique();  // Tambahkan UNIQUE KEY
            $table->string('tahun_angkatan', 2);  // 2 digit tahun angkatan
            $table->string('nama_orang_tua', 100);
            $table->enum('status', ['Aktif', 'Tidak Aktif', 'Izin', 'Sakit']);
            $table->binary('sidik_jari')->nullable();  // Data sidik jari dalam bentuk binary
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('santri');
    }
}