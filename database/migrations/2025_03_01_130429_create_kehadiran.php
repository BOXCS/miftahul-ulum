<?php

// database/migrations/xxxx_xx_xx_create_kehadiran_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKehadiran extends Migration
{
    public function up()
    {
        Schema::create('kehadiran', function (Blueprint $table) {
            $table->id();
            $table->string('santri_id', 10);  // Foreign key ke tabel santri
            $table->string('nama_santri', 100);
            $table->time('jam_masuk')->nullable();
            $table->time('jam_keluar')->nullable();
            $table->enum('status', ['Hadir', 'Tidak Hadir', 'Izin', 'Sakit']);
            $table->dateTime('tanggal_waktu');
            $table->timestamps();

            // Foreign key ke tabel santri
            $table->foreign('santri_id')->references('id')->on('santri')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kehadiran');
    }
}