<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class KendaraanSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // 1. Ambil semua ID sebagai Collection (Hapus ->toArray() agar bisa pakai ->random())
        $perusahaanIds = DB::table('perusahaan')->pluck('id');
        $jenisKendaraanIds = DB::table('jenis_kendaraan')->pluck('id');

        // Validasi: Pastikan data master ada
        if ($perusahaanIds->isEmpty() || $jenisKendaraanIds->isEmpty()) {
            $this->command->warn('Data Perusahaan atau Jenis Kendaraan masih kosong. Seeding dibatalkan.');
            return;
        }

        $kendaraan = [];

        // 2. Loop sebanyak 15 kali untuk membuat 15 item
        for ($i = 1; $i <= 15; $i++) {
            
            // Generate Plat Nomor Random (Contoh: B 1234 ABC)
            $kodeWilayah = Arr::random(['B', 'D', 'F', 'L', 'N', 'DK']); 
            $nomorPlat = rand(1000, 9999);
            $kodeBelakang = Str::upper(Str::random(2)); // 2 Huruf acak

            $kendaraan[] = [
                // Ambil 1 ID Perusahaan secara acak
                'id_perusahaan' => $perusahaanIds->random(),
                
                // Ambil 1 ID Jenis Kendaraan secara acak
                'id_jenis_kendaraan' => $jenisKendaraanIds->random(),
                
                'nama' => 'Unit Operasional #' . str_pad($i, 3, '0', STR_PAD_LEFT), // Cth: Unit Operasional #001
                'plat' => "$kodeWilayah $nomorPlat $kodeBelakang",
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('kendaraan')->insert($kendaraan);
    }
}