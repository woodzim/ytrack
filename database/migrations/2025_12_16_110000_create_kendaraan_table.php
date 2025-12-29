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
        Schema::create('kendaraan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_perusahaan');
            $table->unsignedBigInteger('id_jenis_kendaraan');
            $table->string('nama');
            $table->string('plat');
            $table->timestamps();

            // Foreign key constraint with cascade delete
            $table->foreign('id_perusahaan')
                    ->references('id')
                    ->on('perusahaan');

            $table->foreign('id_jenis_kendaraan')
                    ->references('id')
                    ->on('jenis_kendaraan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendaraan');
    }
};
