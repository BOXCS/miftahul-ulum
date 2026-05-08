<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSantriTable extends Migration
{
    public function up()
    {
        Schema::create('santri', function (Blueprint $table) {
            $table->char('id_santri', 9)->primary();
            $table->string('nama');
            $table->string('tahun_angkatan', 4);
            $table->binary('sidik_jari')->nullable();
            $table->enum('status', ['aktif', 'tidak aktif'])->default('aktif');
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