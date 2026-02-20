<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Perusahaan\KendaraanController as PerusahaanKendaraanController;
use App\Http\Controllers\Perusahaan\AlatController as PerusahaanAlatController;

Route::resource('kendaraan', PerusahaanKendaraanController::class);
Route::get('/alat', [PerusahaanAlatController::class, 'index'])->name('alat.index');
