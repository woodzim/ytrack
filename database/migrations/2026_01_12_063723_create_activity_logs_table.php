<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            // Siapa yang melakukan (Admin/User)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Jenis Aksi: CREATE, UPDATE, DELETE, LOGIN, CHANGE_PASSWORD, REGISTER
            $table->string('action'); 
            
            // Target Model: Kendaraan, Alat, SesiAlat, atau User
            $table->string('subject_type')->nullable(); 
            // ID dari target tersebut
            $table->unsignedBigInteger('subject_id')->nullable(); 
            
            // Deskripsi detail (misal: "Mengubah plat nomor dari B 1234 ke B 5678")
            $table->text('description')->nullable();
            
            // IP Address untuk keamanan
            $table->string('ip_address')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};