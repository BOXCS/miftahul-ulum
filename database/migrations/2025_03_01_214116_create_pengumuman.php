<?php

// database/migrations/xxxx_xx_xx_create_pengumuman_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengumuman extends Migration
{
    public function up()
    {
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 100);
            $table->text('isi');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('kategori', ['Akademik', 'Kedisiplinan', 'Kegiatan']);
            $table->string('foto')->nullable();  // Kolom untuk menyimpan path/lokasi file foto
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengumuman');
    }
}