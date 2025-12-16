<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardsController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\AlatController;
use App\Http\Controllers\Controller;

Route::get('/', function () {
    return view('welcome');
});

/******** Dashboards ********/
Route::get('/', function () {
    return redirect('index'); // This will redirect '/' to '/index'
});

// Route::get('/', [DashboardsController::class, 'index']);
Route::get('index', [DashboardsController::class, 'index']);

/******** Perusahaan ********/
Route::resource('perusahaan', PerusahaanController::class);

/******** Kendaraan ********/
Route::post('perusahaan/{perusahaan}/kendaraan', [KendaraanController::class, 'store'])->name('kendaraan.store');
Route::put('perusahaan/{perusahaan}/kendaraan/{kendaraan}', [KendaraanController::class, 'update'])->name('kendaraan.update');
Route::delete('perusahaan/{perusahaan}/kendaraan/{kendaraan}', [KendaraanController::class, 'destroy'])->name('kendaraan.destroy');

/******** Alat ********/
Route::resource('alat', AlatController::class);