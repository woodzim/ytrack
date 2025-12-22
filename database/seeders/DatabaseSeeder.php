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
        // User::factory(10)->create();

        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        $admin1 = User::firstOrCreate(
            ['email' => 'admin@dumeg.id'],
            [
            'name' => 'Admin',
            'password' => bcrypt('admindmg123'),
            ]
        );

        // Assign admin role
        $admin1->assignRole($adminRole);

        $admin2 = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
            'name' => 'Test User',
            ]
        );

        // Assign admin role
        $admin2->assignRole($adminRole);

        // Create company account
        $perusahaan = Perusahaan::firstOrCreate(
            ['nama' => 'PT. Dummy Company'],
            ['alamat' => 'Jakarta, Indonesia']
        );

        $perusahaanRole = Role::firstOrCreate(['name' => 'perusahaan']);

        $userPerusahaan = User::firstOrCreate(
            ['email' => 'perusahaan@dumeg.id'],
            [
                'name' => 'Perusahaan Account',
                'password' => bcrypt('perusahaan123'),
                'id_perusahaan' => $perusahaan->id,
            ]
        );

        // Assign perusahaan role
        $userPerusahaan->assignRole($perusahaanRole);
    }
}
