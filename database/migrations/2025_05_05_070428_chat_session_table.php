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
        Schema::create('chat_sessions', function (Blueprint $table) {
            $table->id('id_session');
            $table->unsignedBigInteger('id_staf');
            $table->unsignedBigInteger('id_ortu');
            $table->timestamp('last_message_time')->nullable(); // Waktu pesan terakhir
            $table->text('last_message')->nullable(); // Pesan terakhir (untuk preview sidebar)
            $table->unsignedInteger('unread_count')->default(0); // Jumlah pesan baru (untuk badge baru)
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_staf')->references('id_staf')->on('staff')->onDelete('cascade');
            $table->foreign('id_ortu')->references('id_ortu')->on('orang_tua')->onDelete('cascade');

            // Unique supaya tiap ortu-staf hanya punya 1 session
            $table->unique(['id_staf', 'id_ortu']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('chat_sessions');
    }
};
