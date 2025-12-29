<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PerusahaanSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Kita gunakan insertOrIgnore atau upsert agar tidak error jika dijalankan berulang
        DB::table('perusahaan')->upsert([
            [
                'id' => 2, // ID manual agar mudah direlasikan nanti (ID 1 biasanya dari UserSeeder)
                'nama' => 'PT. Logistik Nusantara',
                'alamat' => 'Jl. Pelabuhan No. 88, Surabaya',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'nama' => 'CV. Angkut Cepat',
                'alamat' => 'Jl. Soekarno Hatta No. 12, Bandung',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ], ['id'], ['nama', 'alamat']); // Update jika ID sudah ada
    }
}