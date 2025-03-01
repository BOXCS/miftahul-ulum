<?php

// database/migrations/xxxx_xx_xx_create_orang_tua_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrangTua extends Migration
{
    public function up()
    {
        Schema::create('orang_tua', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->text('alamat');
            $table->string('email', 100)->unique();
            $table->string('no_telp', 15);
            $table->string('santri_id', 10);  // Merujuk ke kolom `id` di tabel `santri`
            $table->string('password');
            $table->timestamps();

            // Foreign key ke tabel santri
            $table->foreign('santri_id')->references('id')->on('santri')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orang_tua');
    }
}