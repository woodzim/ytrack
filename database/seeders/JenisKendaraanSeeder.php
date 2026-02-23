<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisKendaraan;
use Carbon\Carbon;

class JenisKendaraanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // --- KATEGORI: KENDARAAN (10 Item) ---
            ['nama' => 'Blind Van', 'kategori' => 'Kendaraan'],
            ['nama' => 'Pick Up Bak', 'kategori' => 'Kendaraan'],
            ['nama' => 'Pick Up Box', 'kategori' => 'Kendaraan'],
            ['nama' => 'Truk Engkel (CDE)', 'kategori' => 'Kendaraan'],
            ['nama' => 'Truk CDD (Double)', 'kategori' => 'Kendaraan'],
            ['nama' => 'Truk Fuso', 'kategori' => 'Kendaraan'],
            ['nama' => 'Truk Tronton', 'kategori' => 'Kendaraan'],
            ['nama' => 'Truk Wingbox', 'kategori' => 'Kendaraan'],
            ['nama' => 'Trailer Flatbed', 'kategori' => 'Kendaraan'],
            ['nama' => 'Trailer Lowbed', 'kategori' => 'Kendaraan'],

            // --- KATEGORI: CONTAINER (5 Item) ---
            ['nama' => 'Container 20 Feet', 'kategori' => 'Container'],
            ['nama' => 'Container 40 Feet', 'kategori' => 'Container'],
            ['nama' => 'Container 40 Feet High Cube', 'kategori' => 'Container'],
            ['nama' => 'Container Reefer (Pendingin)', 'kategori' => 'Container'],
            ['nama' => 'Container Open Top', 'kategori' => 'Container'],
        ];

        foreach ($data as $item) {
            JenisKendaraan::firstOrCreate(
                ['nama' => $item['nama']],
                ['kategori' => $item['kategori']]
            );
        }
    }
}