<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Perusahaan\SesiAlatController as PerusahaanSesiAlatController;

Route::put('/perjalanan/{id}/toggle', [PerusahaanSesiAlatController::class, 'toggle'])
    ->name('perjalanan.toggle');

Route::resource('perjalanan', PerusahaanSesiAlatController::class)->parameters(['perjalanan' => 'sesi_alat']);
