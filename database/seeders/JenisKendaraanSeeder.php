<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JenisKendaraanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $data = [
            // --- KATEGORI: KENDARAAN (10 Item) ---
            [
                'nama' => 'Blind Van',
                'kategori' => 'Kendaraan',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'nama' => 'Pick Up Bak',
                'kategori' => 'Kendaraan',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'nama' => 'Pick Up Box',
                'kategori' => 'Kendaraan',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'nama' => 'Truk Engkel (CDE)',
                'kategori' => 'Kendaraan',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'nama' => 'Truk CDD (Double)',
                'kategori' => 'Kendaraan',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'nama' => 'Truk Fuso',
                'kategori' => 'Kendaraan',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'nama' => 'Truk Tronton',
                'kategori' => 'Kendaraan',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'nama' => 'Truk Wingbox',
                'kategori' => 'Kendaraan',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'nama' => 'Trailer Flatbed',
                'kategori' => 'Kendaraan',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'nama' => 'Trailer Lowbed',
                'kategori' => 'Kendaraan',
                'created_at' => $now, 'updated_at' => $now,
            ],

            // --- KATEGORI: CONTAINER (5 Item) ---
            [
                'nama' => 'Container 20 Feet',
                'kategori' => 'Container',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'nama' => 'Container 40 Feet',
                'kategori' => 'Container',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'nama' => 'Container 40 Feet High Cube',
                'kategori' => 'Container',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'nama' => 'Container Reefer (Pendingin)',
                'kategori' => 'Container',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'nama' => 'Container Open Top',
                'kategori' => 'Container',
                'created_at' => $now, 'updated_at' => $now,
            ],
        ];

        // Masukkan data ke tabel
        DB::table('jenis_kendaraan')->insert($data);
    }
}