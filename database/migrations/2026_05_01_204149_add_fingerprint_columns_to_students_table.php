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
        Schema::table("students", function (Blueprint $table) {
            $table
                ->text("fingerprint_template")
                ->nullable()
                ->comment("Encrypted fingerprint template");
            $table
                ->integer("fingerprint_quality")
                ->nullable()
                ->comment("Quality of the fingerprint scan (percentage)");
            $table
                ->timestamp("scanned_at")
                ->nullable()
                ->comment("Time when the fingerprint was recorded");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("students", function (Blueprint $table) {
            $table->dropColumn([
                "fingerprint_template",
                "fingerprint_quality",
                "scanned_at",
            ]);
        });
    }
};
