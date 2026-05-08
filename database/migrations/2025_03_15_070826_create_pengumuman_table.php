<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengumumanTable extends Migration
{
    public function up()
    {
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id('id_pengumuman');
            $table->string('judul');
            $table->text('isi');
            $table->date('tgl_mulai'); // Bisa diganti dengan timestamp jika perlu waktu juga
            $table->enum('kategori', ['akademik', 'administrasi', 'kegiatan'])->index();
            $table->string('foto')->nullable(); // Simpan path gambar, bukan binary data
            $table->unsignedBigInteger('id_akun');
            $table->foreign('id_akun')->references('id_akun')->on('akun')->onDelete('cascade');
            $table->timestamps();
        });        
    }

    public function down()
    {
        Schema::dropIfExists('pengumuman');
    }
}