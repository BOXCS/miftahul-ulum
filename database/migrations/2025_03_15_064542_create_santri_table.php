<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSantriTable extends Migration
{
    public function up()
    {
        Schema::create('santri', function (Blueprint $table) {
            $table->id('id_santri')->nullable();
            $table->string('nama');
            $table->string('tahun_angkatan', 2);
            $table->binary('sidik_jari')->nullable();
            $table->enum('status', ['aktif', 'tidak aktif'])->nullable();
            $table->unsignedBigInteger('id_ortu');
            $table->foreign('id_ortu')->references('id_ortu')->on('orang_tua')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('santri');
    }
}