<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTglSelesaiToPengumumanTable extends Migration
{
    public function up()
    {
        Schema::table('pengumuman', function (Blueprint $table) {
            $table->date('tgl_selesai')->nullable(); // Menambahkan kolom tgl_selesai
        });
    }

    public function down()
    {
        Schema::table('pengumuman', function (Blueprint $table) {
            $table->dropColumn('tgl_selesai'); // Menghapus kolom tgl_selesai jika migrasi dibatalkan
        });
    }
}
