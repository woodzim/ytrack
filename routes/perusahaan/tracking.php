<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Perusahaan\SesiAlatController as PerusahaanSesiAlatController;

Route::put('/sesi-alat/{id}/toggle', [PerusahaanSesiAlatController::class, 'toggle'])
    ->name('sesi-alat.toggle');

Route::resource('sesi-alat', PerusahaanSesiAlatController::class);
