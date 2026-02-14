<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kendaraan;
use App\Models\Perusahaan;
use App\Models\JenisKendaraan;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class KendaraanSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil semua ID sebagai Collection
        $perusahaanIds = Perusahaan::pluck('id');
        $jenisKendaraanIds = JenisKendaraan::pluck('id');

        // Validasi: Pastikan data master ada
        if ($perusahaanIds->isEmpty() || $jenisKendaraanIds->isEmpty()) {
            $this->command->warn('Data Perusahaan atau Jenis Kendaraan masih kosong. Seeding dibatalkan.');
            return;
        }

        // 2. Loop sebanyak 15 kali untuk membuat 15 item
        for ($i = 1; $i <= 15; $i++) {
            
            // Generate Plat Nomor Random (Contoh: B 1234 ABC)
            $kodeWilayah = Arr::random(['B', 'D', 'F', 'L', 'N', 'DK']); 
            $nomorPlat = rand(1000, 9999);
            $kodeBelakang = Str::upper(Str::random(2)); // 2 Huruf acak

            Kendaraan::create([
                'id_perusahaan' => $perusahaanIds->random(),
                'id_jenis_kendaraan' => $jenisKendaraanIds->random(),
                'nama' => 'Unit Operasional #' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'plat' => "$kodeWilayah $nomorPlat $kodeBelakang",
            ]);
        }
    }
}