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
        Schema::create('alat', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('kode_alat')->unique();
            $table->unsignedBigInteger('id_perusahaan')->nullable();
            $table->timestamps();

            $table->foreign('id_perusahaan')
                ->references('id')
                ->on('perusahaan')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alat');
    }
};
