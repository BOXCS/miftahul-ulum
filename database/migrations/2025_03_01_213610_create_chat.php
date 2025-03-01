<?php

// database/migrations/xxxx_xx_xx_create_chat_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChat extends Migration
{
    public function up()
    {
        Schema::create('chat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');  // Foreign key ke tabel admin
            $table->unsignedBigInteger('orang_tua_id');  // Foreign key ke tabel orang_tua
            $table->text('pesan');
            $table->timestamp('waktu_kirim');
            $table->timestamps();

            // Foreign key ke tabel admin dan orang_tua
            $table->foreign('admin_id')->references('id')->on('admin')->onDelete('cascade');
            $table->foreign('orang_tua_id')->references('id')->on('orang_tua')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('chat');
    }
}