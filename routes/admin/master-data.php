<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PerusahaanController;
use App\Http\Controllers\Admin\JenisKendaraanController;

/******** Perusahaan ********/
Route::resource('perusahaan', PerusahaanController::class);

/******** Jenis Kendaraan ********/
Route::resource('jenis-kendaraan', JenisKendaraanController::class);
