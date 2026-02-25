<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Perusahaan;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class VerifiedCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Pastikan Role 'perusahaan' ada
        $perusahaanRole = Role::firstOrCreate(['name' => 'perusahaan']);

        // 2. Buat Data Perusahaan
        $perusahaan = Perusahaan::firstOrCreate(
            ['nama' => 'PT. Terverifikasi Abadi'],
            [
                'alamat' => 'Jl. Merdeka No. 1, Jakarta Pusat',
            ]
        );

        // 3. Buat User yang langsung aktif & terverifikasi
        $user = User::firstOrCreate(
            ['email' => 'perusahaan@dumeg.id'], // Email diperbarui
            [
                'name' => 'Admin Perusahaan Utama',
                'password' => Hash::make('perusahaan123'), // Password diperbarui
                'id_perusahaan' => $perusahaan->id,
                'is_validated' => true, 
                'email_verified_at' => Carbon::now(),
            ]
        );

        // 4. Assign Role
        if (!$user->hasRole('perusahaan')) {
            $user->assignRole($perusahaanRole);
        }

        // 5. Pindahkan dari UserSeeder: PT. Dummy Company
        $dummyPerusahaan = Perusahaan::firstOrCreate(
            ['nama' => 'PT. Dummy Company'],
            [
                'alamat' => 'Jakarta, Indonesia',
            ]
        );

        $dummyUser = User::firstOrCreate(
            ['email' => 'dummy@dumeg.id'],
            [
                'name' => 'Admin Dummy Company',
                'password' => Hash::make('dummy123'),
                'id_perusahaan' => $dummyPerusahaan->id,
                'is_validated' => true,
                'email_verified_at' => Carbon::now(),
            ]
        );
        
        if (!$dummyUser->hasRole('perusahaan')) {
            $dummyUser->assignRole($perusahaanRole);
        }

        $this->command->info('User Perusahaan Terverifikasi berhasil dibuat!');
        $this->command->info('Email: verified@dumeg.id');
        $this->command->info('Password: password');
        $this->command->info('---------------------------------------');
        $this->command->info('User Dummy Company berhasil dibuat!');
        $this->command->info('Email: dummy@dumeg.id');
        $this->command->info('Password: dummy123');
    }
}
