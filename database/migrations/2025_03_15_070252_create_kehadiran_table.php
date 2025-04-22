<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKehadiranTable extends Migration
{
    public function up()
    {
        Schema::create('kehadiran', function (Blueprint $table) {
            $table->id('id_kehadiran');
            $table->date('waktu');
            $table->timestamp('jam_masuk')->nullable();
            $table->timestamp('jam_keluar')->nullable();
            $table->enum('waktu_shalat', ['Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya']);
            $table->String('id_santri')->index();
            $table->foreign('id_santri')->references('id_santri')->on('santri')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kehadiran');
    }
}
