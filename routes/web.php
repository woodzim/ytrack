<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Admin\DashboardsController;
use App\Http\Controllers\Admin\PerusahaanController;
use App\Http\Controllers\Admin\KendaraanController;
use App\Http\Controllers\Admin\AlatController;
use App\Http\Controllers\Admin\SesiAlatController;
use App\Http\Controllers\Admin\RiwayatPerjalananController;
use App\Http\Controllers\Admin\TrackingTidakLengkapController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Perusahaan\KendaraanController as PerusahaanKendaraanController;
use App\Http\Controllers\Perusahaan\SesiAlatController as PerusahaanSesiAlatController;
use App\Http\Controllers\Perusahaan\DashboardController as PerusahaanDashboardController;

Route::redirect('/', 'dashboard');

/******** Authentication ********/
Route::get('login', [LoginController::class, 'showLogin'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.store');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('register', [RegisterController::class, 'showRegister'])->name('register');
Route::post('register', [RegisterController::class, 'register'])->name('register.store');

Route::middleware(['auth'])->group(function () {
    Route::middleware('role:perusahaan')->group(function () {
        /******** Kendaraan for Perusahaan ********/
        Route::resource('kendaraan', PerusahaanKendaraanController::class);

        /******** Sesi Alat for Perusahaan ********/
        Route::prefix('perusahaan')->name('perusahaan.')->group(function () {
            /******** Dashboard for Perusahaan ********/
            Route::get('dashboard', [PerusahaanDashboardController::class, 'index'])->name('dashboard.index');
            Route::put('dashboard', [PerusahaanDashboardController::class, 'update'])->name('dashboard.update');

            Route::resource('sesi-alat', PerusahaanSesiAlatController::class, [
                'only' => ['index', 'show']
            ])->names('sesi-alat');
        });
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('dashboard', [DashboardsController::class, 'index'])->name('dashboard');
        /******** Dashboards ********/

        /******** Pengguna ********/
        Route::get('users', [UsersController::class, 'index'])->name('users.index');

        /******** Perusahaan ********/
        Route::resource('perusahaan', PerusahaanController::class);

        /******** Kendaraan ********/
        Route::post('perusahaan/{perusahaan}/kendaraan', [KendaraanController::class, 'store'])->name('admin.kendaraan.store');
        Route::put('perusahaan/{perusahaan}/kendaraan/{kendaraan}', [KendaraanController::class, 'update'])->name('admin.kendaraan.update');
        Route::delete('perusahaan/{perusahaan}/kendaraan/{kendaraan}', [KendaraanController::class, 'destroy'])->name('admin.kendaraan.destroy');

        /******** Alat ********/
        Route::resource('alat', AlatController::class);

        /******** Sesi Alat ********/
        Route::resource('sesi-alat', SesiAlatController::class);
        Route::put('sesi-alat/{sesiAlat}/toggle', [SesiAlatController::class, 'toggle'])->name('sesi-alat.toggle');
        Route::get('sesi-alat-kendaraan/{idPerusahaan}', [SesiAlatController::class, 'getKendaraanByPerusahaan'])->name('sesi-alat.kendaraan');

        /******** Riwayat Perjalanan (Orphan Data) ********/
        Route::resource('riwayat-perjalanan', RiwayatPerjalananController::class, [
            'only' => ['index', 'show', 'edit', 'update', 'destroy']
        ]);

        /******** Tracking Tidak Lengkap ********/
        Route::resource('tracking-tidak-lengkap', TrackingTidakLengkapController::class, [
            'only' => ['index', 'edit', 'update', 'destroy']
        ]);
    });
});
