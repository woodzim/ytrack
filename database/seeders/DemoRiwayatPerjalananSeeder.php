<?php

namespace Database\Seeders;

use App\Models\Alat;
use App\Models\JenisKendaraan;
use App\Models\Kendaraan;
use App\Models\Perusahaan;
use App\Models\RiwayatPerjalanan;
use App\Models\SesiAlat;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoRiwayatPerjalananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil atau buat Perusahaan dan JenisKendaraan
        $perusahaan = Perusahaan::first() ?? Perusahaan::create([
            'nama' => 'Perusahaan Demo',
            'alamat' => 'Alamat Demo'
        ]);
        
        $jenisKendaraan = JenisKendaraan::first() ?? JenisKendaraan::create([
            'nama' => 'Truk Demo',
            'kategori' => 'Kendaraan'
        ]);

        // 2. Tentukan Tanggal Acak (Dalam 7 hari terakhir)
        $daysAgo = rand(0, 6);
        $baseDate = Carbon::now()->subDays($daysAgo)->setHour(rand(6, 10))->setMinute(rand(0, 59));
        
        // 3. Acak Titik Awal (Sekitar Jakarta) dan Arah Pergerakan
        $startLat = -6.2088 + (rand(-100, 100) / 1000); 
        $startLng = 106.8456 + (rand(-100, 100) / 1000); 
        $latStep = (rand(-5, 5) / 10000); 
        $lngStep = (rand(-5, 5) / 10000); 

        // 4. Buat 1 Alat
        $alatId = 'DEMO-' . strtoupper(bin2hex(random_bytes(2)));
        $alat = Alat::create([
            'id' => $alatId,
            'id_perusahaan' => $perusahaan->id,
        ]);

        // 5. Buat 1 Kendaraan
        $kendaraan = Kendaraan::create([
            'id_perusahaan' => $perusahaan->id,
            'id_jenis_kendaraan' => $jenisKendaraan->id,
            'nama' => 'Kendaraan Demo ' . $alatId,
            'plat' => 'B ' . rand(1000, 9999) . ' DM',
        ]);

        // 6. Buat 1 SesiAlat (Set aktif jika hari ini, set waktu sesuai baseDate)
        $isToday = $daysAgo === 0;
        $sesiAlat = SesiAlat::create([
            'id_alat' => $alat->id,
            'id_kendaraan' => $kendaraan->id,
            'id_perusahaan' => $perusahaan->id,
            'nama' => 'Sesi Demo ' . $alatId . ' (' . $baseDate->format('d M') . ')',
            'aktif' => $isToday,
            'created_at' => $baseDate,
            'updated_at' => $isToday ? Carbon::now() : $baseDate->copy()->addMinutes(120),
        ]);

        // 7. Generate 2000 titik RiwayatPerjalanan
        $points = [];
        $currentLat = $startLat;
        $currentLng = $startLng;

        $this->command->info("Generating 2000 points for $alatId on date: " . $baseDate->toDateTimeString());

        for ($i = 0; $i < 2000; $i++) {
            $currentLat += $latStep + (rand(-5, 5) / 100000);
            $currentLng += $lngStep + (rand(-5, 5) / 100000);
            
            // Tambahkan 10 detik per titik
            $timestamp = $baseDate->copy()->addSeconds($i * 10);
            
            // Jangan generate titik melampaui waktu sekarang
            if ($timestamp->isFuture()) break;

            $points[] = [
                'id' => (string) Str::uuid(),
                'id_sesi_alat' => $sesiAlat->id,
                'id_perusahaan' => $perusahaan->id,
                'id_kendaraan' => $kendaraan->id,
                'id_alat' => $alat->id,
                'lat' => (string) $currentLat,
                'long' => (string) $currentLng,
                'alt' => (string) rand(10, 50),
                'sat' => rand(8, 12),
                'speed' => rand(20, 80),
                'battery' => (string) rand(20, 100),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];

            if (count($points) >= 500) {
                RiwayatPerjalanan::insert($points);
                $points = [];
            }
        }

        if (count($points) > 0) {
            RiwayatPerjalanan::insert($points);
        }

        $this->command->info("Seeding completed for $alatId!");
    }
}
