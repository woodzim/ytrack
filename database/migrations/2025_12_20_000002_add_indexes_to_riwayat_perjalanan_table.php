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
        Schema::table('riwayat_perjalanan', function (Blueprint $table) {
            // Index for filtering by id_sesi_alat
            $table->index('id_sesi_alat');
            
            // Composite index for filtering by id_sesi_alat and sorting by created_at
            $table->index(['id_sesi_alat', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayat_perjalanan', function (Blueprint $table) {
            $table->dropIndex(['id_sesi_alat']);
            $table->dropIndex(['id_sesi_alat', 'created_at']);
        });
    }
};
