<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
// Admin Controllers
use App\Http\Controllers\Admin\DashboardsController;
use App\Http\Controllers\Admin\PerusahaanController;
use App\Http\Controllers\Admin\KendaraanController;
use App\Http\Controllers\Admin\JenisKendaraanController;
use App\Http\Controllers\Admin\AlatController;
use App\Http\Controllers\Admin\SesiAlatController;
use App\Http\Controllers\Admin\RiwayatPerjalananController;
use App\Http\Controllers\Admin\TrackingTidakLengkapController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\LogActivityController;
// Perusahaan Controllers
use App\Http\Controllers\Perusahaan\KendaraanController as PerusahaanKendaraanController;
use App\Http\Controllers\Perusahaan\SesiAlatController as PerusahaanSesiAlatController;
use App\Http\Controllers\Perusahaan\DashboardController as PerusahaanDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->hasRole('perusahaan')) {
            return redirect()->route('perusahaan.dashboard.index');
        }
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});
/******** Authentication ********/
Route::get('login', [LoginController::class, 'showLogin'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.store');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('register', [RegisterController::class, 'showRegister'])->name('register');
Route::post('register', [RegisterController::class, 'register'])->name('register.store');


Route::middleware(['auth'])->group(function () {

    // =================================================================
    // ROLE: PERUSAHAAN
    // =================================================================
    Route::middleware('role:perusahaan')
        ->prefix('perusahaan')          // URL akan diawali /perusahaan/..
        ->name('perusahaan.')           // Nama route diawali perusahaan... (cth: perusahaan.kendaraan.index)
        ->group(function () {

            // Dashboard
            Route::get('dashboard', [PerusahaanDashboardController::class, 'index'])->name('dashboard.index');
            Route::put('dashboard', [PerusahaanDashboardController::class, 'update'])->name('dashboard.update');

            Route::put('/sesi-alat/{id}/toggle', [PerusahaanSesiAlatController::class, 'toggle'])
                ->name('sesi-alat.toggle');
                
            Route::resource('kendaraan', PerusahaanKendaraanController::class);
                
            Route::resource('sesi-alat', PerusahaanSesiAlatController::class);
        });

    // =================================================================
    // ROLE: ADMIN
    // =================================================================
    Route::middleware('role:admin')->group(function () {
        
        Route::get('dashboard', [DashboardsController::class, 'index'])->name('dashboard');

        /******** Profile ********/
        Route::get('profile', [ProfileController::class, 'index'])->name('admin.profile.index');
        Route::put('profile', [ProfileController::class, 'update'])->name('admin.profile.update');

        /******** Pengguna ********/
        Route::get('users', [UsersController::class, 'index'])->name('users.index');
        Route::put('users/{id}/password', [UsersController::class, 'updatePassword'])->name('users.updatePassword');
        Route::put('users/{id}/validate', [UsersController::class, 'validateUser'])->name('users.validate');
        Route::put('users/{id}/reject', [UsersController::class, 'rejectUser'])->name('users.reject');

        /******** Perusahaan ********/
        Route::resource('perusahaan', PerusahaanController::class);

        /******** Jenis Kendaraan ********/
        Route::resource('jenis-kendaraan', JenisKendaraanController::class);
        
        /******** Kendaraan (Admin) ********/
        // Route Name: kendaraan.index (Tanpa prefix, sesuai sidebar Admin)
        Route::resource('kendaraan', KendaraanController::class);

        /******** Alat ********/
        Route::resource('alat', AlatController::class);

        /******** Sesi Alat (Admin) ********/
        Route::resource('sesi-alat', SesiAlatController::class);
        Route::put('sesi-alat/{sesiAlat}/toggle', [SesiAlatController::class, 'toggle'])->name('sesi-alat.toggle');
        
        // Helper routes untuk AJAX/Dropdown
        Route::get('sesi-alat-kendaraan/{idPerusahaan}', [SesiAlatController::class, 'getKendaraanByPerusahaan'])->name('sesi-alat.kendaraan');
        Route::get('sesi-alat-alat/{idPerusahaan}', [SesiAlatController::class, 'getAlatByPerusahaan'])->name('sesi-alat.alat');
        
        /******** Riwayat Perjalanan ********/
        Route::resource('riwayat-perjalanan', RiwayatPerjalananController::class, [
            'only' => ['index', 'show', 'edit', 'update', 'destroy']
        ]);

        /******** Tracking Tidak Lengkap ********/
        Route::resource('tracking-tidak-lengkap', TrackingTidakLengkapController::class, [
            'only' => ['index', 'edit', 'update', 'destroy']
        ]);

        Route::get('/logs', [LogActivityController::class, 'index'])->name('logs.index');
    });
});