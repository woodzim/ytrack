<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SesiAlatController;
use App\Http\Controllers\Admin\RiwayatPerjalananController;
use App\Http\Controllers\Admin\TrackingTidakLengkapController;

/******** Perjalanan (Admin) ********/
Route::resource('perjalanan', SesiAlatController::class)->parameters(['perjalanan' => 'sesi_alat']);
Route::put('perjalanan/{sesiAlat}/toggle', [SesiAlatController::class, 'toggle'])->name('perjalanan.toggle');

// Helper routes untuk AJAX/Dropdown
Route::get('perjalanan-kendaraan/{idPerusahaan}', [SesiAlatController::class, 'getKendaraanByPerusahaan'])->name('perjalanan.kendaraan');
Route::get('perjalanan-alat/{idPerusahaan}', [SesiAlatController::class, 'getAlatByPerusahaan'])->name('perjalanan.alat');

/******** Riwayat Perjalanan ********/
Route::resource('riwayat-perjalanan', RiwayatPerjalananController::class, [
    'only' => ['index', 'show', 'edit', 'update', 'destroy']
]);

/******** Tracking Tidak Lengkap ********/
Route::resource('tracking-tidak-lengkap', TrackingTidakLengkapController::class, [
    'only' => ['index', 'edit', 'update', 'destroy']
]);
