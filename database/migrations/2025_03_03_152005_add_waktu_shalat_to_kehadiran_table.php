<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWaktuShalatToKehadiranTable extends Migration
{
    public function up()
    {
        Schema::table('kehadiran', function (Blueprint $table) {
            $table->enum('waktu_shalat', ['Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya'])->nullable();
        });
    }

    public function down()
    {
        Schema::table('kehadiran', function (Blueprint $table) {
            $table->dropColumn('waktu_shalat');
        });
    }
}