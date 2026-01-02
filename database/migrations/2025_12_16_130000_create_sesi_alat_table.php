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
        Schema::create('sesi_alat', function (Blueprint $table) {
            $table->id();
            $table->string('id_alat')->nullable();
            $table->unsignedBigInteger('id_kendaraan')->nullable();
            $table->unsignedBigInteger('id_perusahaan')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_alat')
                    ->references('id')
                    ->on('alat')
                    ->cascadeOnDelete();

            $table->foreign('id_kendaraan')
                    ->references('id')
                    ->on('kendaraan')
                    ->cascadeOnDelete();

            $table->foreign('id_perusahaan')
                    ->references('id')
                    ->on('perusahaan')
                    ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesi_alat');
    }
};
