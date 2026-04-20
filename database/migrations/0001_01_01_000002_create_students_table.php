<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("students", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("parent_id")
                ->nullable()
                ->constrained("parents")
                ->onDelete("set null");
            $table->string("name");
            $table->string("nis", 20)->unique()->nullable();
            $table->enum("gender", ["Laki-laki", "Perempuan"])->nullable();
            $table->date("tanggal_lahir")->nullable();
            $table->string("email")->unique()->nullable();
            $table->string("phone")->nullable();
            $table->string("class")->nullable();
            $table->string("tahun_angkatan", 4)->nullable();
            $table
                ->enum("status", ["aktif", "alumni", "keluar"])
                ->default("aktif");
            $table->text("address")->nullable();
            $table->string("foto")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("students");
    }
};
