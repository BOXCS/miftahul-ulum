<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatTable extends Migration
{
    public function up()
    {
        Schema::create('chat', function (Blueprint $table) {
            $table->id('id_chat'); // Primary key
            $table->unsignedBigInteger('id_staf'); // Foreign key ke tabel staff
            $table->unsignedBigInteger('id_ortu'); // Foreign key ke tabel orang_tua
            $table->text('pesan'); // Kolom untuk menyimpan pesan
            $table->timestamp('waktu')->useCurrent(); // Waktu pengiriman pesan (otomatis diisi saat pesan dibuat)
            $table->enum('pengirim', ['staf', 'orang_tua']); // Kolom untuk menyimpan siapa yang mengirim pesan

            // Foreign key constraints
            $table->foreign('id_staf')->references('id_staf')->on('staff')->onDelete('cascade');
            $table->foreign('id_ortu')->references('id_ortu')->on('orang_tua')->onDelete('cascade');

            // Index untuk foreign key (opsional)
            $table->index('id_staf');
            $table->index('id_ortu');

            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('chat');
    }
}