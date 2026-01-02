<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Perusahaan;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

use function Symfony\Component\String\u;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            JenisKendaraanSeeder::class,
            UserSeeder::class,       
            PerusahaanSeeder::class, 
            KendaraanSeeder::class,
        ]);
    }
}
