<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PerusahaanController;
use App\Http\Controllers\Admin\JenisKendaraanController;

/******** Perusahaan ********/
Route::resource('admin-perusahaan', PerusahaanController::class)->names('admin.perusahaan');

/******** Jenis Kendaraan ********/
Route::resource('jenis-kendaraan', JenisKendaraanController::class);
