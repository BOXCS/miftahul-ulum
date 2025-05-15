<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogLoginTable extends Migration
{
    public function up()
    {
        Schema::create('log_login', function (Blueprint $table) {
            $table->id('id_login');
            $table->timestamp('created_at')->useCurrent();
            $table->unsignedBigInteger('id_akun')->index();
            $table->foreign('id_akun')->references('id_akun')->on('akun')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('log_login');
    }
}