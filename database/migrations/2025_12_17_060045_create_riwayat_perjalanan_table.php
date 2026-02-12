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
        Schema::create('riwayat_perjalanan', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->unsignedBigInteger('id_sesi_alat')->nullable();
            $table->unsignedBigInteger('id_perusahaan')->nullable();
            $table->unsignedBigInteger('id_kendaraan')->nullable();
            $table->string('id_alat')->nullable();

            // GPS data
            $table->string('lat');
            $table->string('long');
            $table->string('alt')->nullable();
            $table->integer('sat')->nullable();
            $table->integer('speed')->nullable();
            $table->string('battery')->nullable();

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_sesi_alat')->references('id')->on('sesi_alat')->nullOnDelete();
            $table->foreign('id_perusahaan')->references('id')->on('perusahaan')->nullOnDelete();
            $table->foreign('id_kendaraan')->references('id')->on('kendaraan')->nullOnDelete();
            $table->foreign('id_alat')->references('id')->on('alat')->nullOnDelete();

            // Indexes
            $table->index('id_sesi_alat');
            $table->index(['id_sesi_alat', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_perjalanan');
    }
};
