<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id('id_message');
            $table->unsignedBigInteger('id_session'); // FK ke chat_sessions
            $table->enum('pengirim', ['staf', 'orang_tua']); // Siapa pengirimnya
            $table->text('pesan');
            $table->timestamp('waktu')->useCurrent();
            $table->boolean('is_read')->default(false); // Status pesan sudah dibaca atau belum
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_session')->references('id_session')->on('chat_sessions')->onDelete('cascade');

            $table->index('id_session');
        });
    }

    public function down()
    {
        Schema::dropIfExists('chat_messages');
    }
};
