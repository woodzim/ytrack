<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Perusahaan;
use App\Models\Role;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Setup Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $perusahaanRole = Role::firstOrCreate(['name' => 'perusahaan']);

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
    }
}
        