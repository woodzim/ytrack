<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KendaraanSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // 1. Ambil semua ID perusahaan
        $perusahaanIds = DB::table('perusahaan')->pluck('id')->toArray();

        // 2. Ambil semua ID jenis kendaraan (PENTING: Ambil ID-nya untuk relasi)
        $jenisKendaraanIds = DB::table('jenis_kendaraan')->pluck('id');

        // Validasi: Pastikan data master (Perusahaan & Jenis Kendaraan) sudah ada
        if (empty($perusahaanIds) || $jenisKendaraanIds->isEmpty()) {
            $this->command->warn('Data Perusahaan atau Jenis Kendaraan masih kosong. Seeding dibatalkan.');
            return;
        }

        $kendaraan = [];

        foreach ($perusahaanIds as $idPerusahaan) {
            // Kita buat 2 kendaraan per perusahaan dengan jenis acak
            
            // Kendaraan 1
            $kendaraan[] = [
                'id_perusahaan' => $idPerusahaan,
                // Mengambil satu ID jenis kendaraan secara acak
                'id_jenis_kendaraan' => $jenisKendaraanIds->random(), 
                'nama' => 'Armada 01 - ' . $idPerusahaan,
                'plat' => 'B ' . rand(1000, 9999) . ' XYZ',
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // Kendaraan 2
            $kendaraan[] = [
                'id_perusahaan' => $idPerusahaan,
                'id_jenis_kendaraan' => $jenisKendaraanIds->random(),
                'nama' => 'Armada 02 - ' . $idPerusahaan,
                'plat' => 'D ' . rand(1000, 9999) . ' ABC',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('kendaraan')->insert($kendaraan);
    }
}