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
        Schema::create('perusahaan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->string('alamat');
            $table->string('berkas')->nullable(); // Legacy / Status flag
            $table->string('berkas_akta')->nullable();
            $table->string('berkas_npwp_badan')->nullable();
            $table->string('berkas_nib')->nullable();
            $table->string('berkas_domisili')->nullable();
            $table->string('berkas_ktp_pj')->nullable();
            $table->string('berkas_npwp_pj')->nullable();
            $table->string('berkas_izin_usaha')->nullable();
            $table->string('berkas_siup')->nullable();
            $table->text('alasan_tolak')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perusahaan');
    }
};
