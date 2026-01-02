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

        $data = [
            [
                'nama' => 'PT. Logistik Nusantara',
                'alamat' => 'Jl. Pelabuhan Tanjung Perak No. 88, Surabaya',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'nama' => 'CV. Angkut Cepat',
                'alamat' => 'Jl. Soekarno Hatta No. 12, Bandung',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'nama' => 'PT. Trans Jawa Express',
                'alamat' => 'Jl. Daan Mogot KM 19, Jakarta Barat',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'nama' => 'PT. Cargo Mandiri Sejahtera',
                'alamat' => 'Jl. Raya Kaligawe No. 45, Semarang',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'nama' => 'CV. Berkah Abadi Trans',
                'alamat' => 'Jl. Ring Road Utara, Yogyakarta',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'nama' => 'PT. Samudera Logistik Indonesia',
                'alamat' => 'Jl. Nusantara, Pelabuhan Makassar',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'nama' => 'PT. Lintas Benua Cargo',
                'alamat' => 'Jl. Krakatau No. 102, Medan',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'nama' => 'CV. Mitra Distribusi',
                'alamat' => 'Jl. Slamet Riyadi No. 200, Solo',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'nama' => 'PT. Sinar Jaya Logistik',
                'alamat' => 'Kawasan Industri MM2100, Bekasi',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'nama' => 'PT. Antar Paket Kilat (APK)',
                'alamat' => 'Jl. Margonda Raya No. 55, Depok',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'nama' => 'CV. Maju Bersama Cargo',
                'alamat' => 'Jl. Ijen Besar No. 4, Malang',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'nama' => 'PT. Ekspedisi Cepat Selamat',
                'alamat' => 'Jl. Jend. Sudirman No. 89, Palembang',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'nama' => 'PT. Global Transportindo',
                'alamat' => 'Jl. Raya Serpong No. 11, Tangerang',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'nama' => 'CV. Roda Niaga',
                'alamat' => 'Jl. Pajajaran No. 33, Bogor',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'nama' => 'PT. Borneo Transindo',
                'alamat' => 'Jl. Mulawarman No. 77, Balikpapan',
                'created_at' => $now, 'updated_at' => $now,
            ],
        ];

        // Hapus data lama jika perlu, atau langsung insert
        // DB::table('perusahaan')->truncate(); // Hati-hati, ini menghapus semua data
        
        DB::table('perusahaan')->insert($data);
    }
}