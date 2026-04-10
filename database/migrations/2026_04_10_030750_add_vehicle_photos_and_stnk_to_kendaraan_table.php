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
        Schema::table('kendaraan', function (Blueprint $table) {
            $table->string('foto_depan')->nullable()->after('plat');
            $table->string('foto_samping_kanan')->nullable()->after('foto_depan');
            $table->string('foto_samping_kiri')->nullable()->after('foto_samping_kanan');
            $table->string('foto_belakang')->nullable()->after('foto_samping_kiri');
            $table->string('stnk')->nullable()->after('foto_belakang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kendaraan', function (Blueprint $table) {
            $table->dropColumn(['foto_depan', 'foto_samping_kanan', 'foto_samping_kiri', 'foto_belakang', 'stnk']);
        });
    }
};
