<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerizinanTable extends Migration
{
    public function up()
    {
        Schema::create('perizinan', function (Blueprint $table) {
            $table->id('id_izin');
            $table->timestamp('waktu');
            $table->enum('jenis_izin', ['izin', 'sakit'])->nullable();
            $table->text('keterangan');
            $table->String('id_santri');
            $table->foreign('id_santri')->references('id_santri')->on('santri')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('perizinan');
    }
}