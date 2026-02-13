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

class DemoRiwayatPerjalananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Get or create Perusahaan and JenisKendaraan
        $perusahaan = Perusahaan::first() ?? Perusahaan::create([
            'nama' => 'Perusahaan Demo',
            'alamat' => 'Alamat Demo'
        ]);
        
        $jenisKendaraan = JenisKendaraan::first() ?? JenisKendaraan::create([
            'nama' => 'Truk Demo',
            'kategori' => 'Kendaraan'
        ]);

        // 2. Create 1 Alat
        $alatId = 'DEMO-' . strtoupper(bin2hex(random_bytes(4)));
        $alat = Alat::create([
            'id' => $alatId,
            'id_perusahaan' => $perusahaan->id,
        ]);

        // 3. Create 1 Kendaraan
        $kendaraan = Kendaraan::create([
            'id_perusahaan' => $perusahaan->id,
            'id_jenis_kendaraan' => $jenisKendaraan->id,
            'nama' => 'Kendaraan Demo ' . $alatId,
            'plat' => 'B ' . rand(1000, 9999) . ' DEMO',
        ]);

        // 4. Create 1 SesiAlat
        $sesiAlat = SesiAlat::create([
            'id_alat' => $alat->id,
            'id_kendaraan' => $kendaraan->id,
            'id_perusahaan' => $perusahaan->id,
            'nama' => 'Sesi Demo ' . $alatId,
            'aktif' => true,
        ]);

        // 5. Generate 2000 points of RiwayatPerjalanan
        $points = [];
        // Jakarta coordinates
        $startLat = -6.2088;
        $startLong = 106.8456;
        $now = Carbon::now()->subSeconds(2000 * 10); // Start long enough ago so the last point is 'now'

        $this->command->info("Generating 2000 points for Alat: $alatId...");

        for ($i = 0; $i < 2000; $i++) {
            // Simulate movement: keep it moving mostly in one direction but with some randomness
            $startLat += (rand(-20, 50) / 100000);
            $startLong += (rand(-20, 50) / 100000);
            
            $timestamp = $now->copy()->addSeconds($i * 10);
            
            $points[] = [
                'id_sesi_alat' => $sesiAlat->id,
                'id_perusahaan' => $perusahaan->id,
                'id_kendaraan' => $kendaraan->id,
                'id_alat' => $alat->id,
                'lat' => (string) $startLat,
                'long' => (string) $startLong,
                'alt' => (string) rand(10, 50),
                'sat' => rand(8, 12),
                'speed' => rand(20, 80),
                'battery' => (string) rand(20, 100),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];

            // Bulk insert every 500 records
            if (count($points) >= 500) {
                RiwayatPerjalanan::insert($points);
                $points = [];
                $this->command->comment("Inserted " . (($i + 1)) . " points...");
            }
        }

        if (count($points) > 0) {
            RiwayatPerjalanan::insert($points);
        }

        $this->command->info("Seeding completed successfully!");
    }
}
