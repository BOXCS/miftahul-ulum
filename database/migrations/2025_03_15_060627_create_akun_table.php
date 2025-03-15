<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAkunTable extends Migration
{
    public function up()
    {
        Schema::create('akun', function (Blueprint $table) {
            $table->id('id_akun');
            $table->string('username')->unique();
            $table->string('password', 60);
            $table->enum('hak_akses', ['admin', 'superadmin', 'ortu']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('akun');
    }
}