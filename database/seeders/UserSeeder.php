<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Perusahaan;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Setup Role Admin
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // 2. Setup Admin Users
        $admin1 = User::firstOrCreate(
            ['email' => 'admin@dumeg.id'],
            [
                'name' => 'Admin',
                'password' => bcrypt('admindmg123'),
            ]
        );
        $admin1->assignRole($adminRole);

        $admin2 = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );
        $admin2->assignRole($adminRole);

        // 3. Setup Data Perusahaan
        $perusahaan = Perusahaan::firstOrCreate(
            ['nama' => 'PT. Dummy Company'],
            [
                'alamat' => 'Jakarta, Indonesia',
                // tambahkan field lain jika ada di tabel perusahaan
            ]
        );

        // 4. Setup Role & User Perusahaan
        $perusahaanRole = Role::firstOrCreate(['name' => 'perusahaan']);

        $userPerusahaan = User::firstOrCreate(
            ['email' => 'perusahaan@dumeg.id'],
            [
                'name' => 'Perusahaan Account',
                'password' => bcrypt('perusahaan123'),
                'id_perusahaan' => $perusahaan->id,
            ]
        );
        $userPerusahaan->assignRole($perusahaanRole);
    }
}