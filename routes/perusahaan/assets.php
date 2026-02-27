<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Perusahaan\KendaraanController as PerusahaanKendaraanController;
use App\Http\Controllers\Perusahaan\AlatController as PerusahaanAlatController;

Route::put('kendaraan/{id}/images/{imageId}', [PerusahaanKendaraanController::class, 'updateImage'])->name('kendaraan.updateImage');
Route::delete('kendaraan/{id}/images/{imageId}', [PerusahaanKendaraanController::class, 'destroyImage'])->name('kendaraan.destroyImage');
Route::resource('kendaraan', PerusahaanKendaraanController::class);

Route::get('/alat', [PerusahaanAlatController::class, 'index'])->name('alat.index');
