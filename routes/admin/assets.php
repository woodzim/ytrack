<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\KendaraanController;
use App\Http\Controllers\Admin\AlatController;

/******** Kendaraan (Admin) ********/
// Route Name: kendaraan.index (Tanpa prefix, sesuai sidebar Admin)
Route::resource('kendaraan', KendaraanController::class);

/******** Alat ********/
Route::resource('alat', AlatController::class);
