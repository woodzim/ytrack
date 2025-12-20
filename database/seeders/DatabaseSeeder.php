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

        if (User::where('email', 'admin@dumeg.id')->doesntExist()) {
            User::factory()->create([
                'name' => 'Admin',
                'email' => 'admin@dumeg.id',
                'password' => bcrypt('admindmg123'),
            ]);
        }

        if  (User::where('email', 'test@example.com')->doesntExist()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

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
