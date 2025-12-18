<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardsController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\AlatController;
use App\Http\Controllers\SesiAlatController;
use App\Http\Controllers\RiwayatPerjalananController;

Route::get('/', function () {
    return view('welcome');
});

/******** Authentication ********/
Route::get('login', [LoginController::class, 'showLogin'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.store');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    /******** Dashboards ********/
    Route::get('dashboard', [DashboardsController::class, 'index'])->name('dashboard');
    Route::redirect('/', 'dashboard');

    /******** Perusahaan ********/
    Route::resource('perusahaan', PerusahaanController::class);

    /******** Kendaraan ********/
    Route::post('perusahaan/{perusahaan}/kendaraan', [KendaraanController::class, 'store'])->name('kendaraan.store');
    Route::put('perusahaan/{perusahaan}/kendaraan/{kendaraan}', [KendaraanController::class, 'update'])->name('kendaraan.update');
    Route::delete('perusahaan/{perusahaan}/kendaraan/{kendaraan}', [KendaraanController::class, 'destroy'])->name('kendaraan.destroy');

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
});
