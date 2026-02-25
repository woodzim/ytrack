<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SesiAlatController;
use App\Http\Controllers\Admin\RiwayatPerjalananController;
use App\Http\Controllers\Admin\TrackingTidakLengkapController;

/******** Perjalanan (Admin) ********/
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
