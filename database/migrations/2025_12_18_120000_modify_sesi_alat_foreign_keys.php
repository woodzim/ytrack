<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sesi_alat', function (Blueprint $table) {
            // Drop existing foreign key constraints
            $table->dropForeign(['id_alat']);
            $table->dropForeign(['id_kendaraan']);

            // Add new foreign key constraints with nullOnDelete
            $table->foreign('id_alat')
                  ->references('id')
                  ->on('alat')
                  ->nullOnDelete();

            $table->foreign('id_kendaraan')
                  ->references('id')
                  ->on('kendaraan')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sesi_alat', function (Blueprint $table) {
            // Drop new foreign key constraints
            $table->dropForeign(['id_alat']);
            $table->dropForeign(['id_kendaraan']);

            // Restore original foreign key constraints with cascadeOnDelete
            $table->foreign('id_alat')
                  ->references('id')
                  ->on('alat')
                  ->cascadeOnDelete();

            $table->foreign('id_kendaraan')
                  ->references('id')
                  ->on('kendaraan')
                  ->cascadeOnDelete();
        });
    }
};
