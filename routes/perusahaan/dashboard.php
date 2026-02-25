<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Perusahaan\DashboardController as PerusahaanDashboardController;
use App\Http\Controllers\Perusahaan\ProfileController as PerusahaanProfileController;

Route::get('dashboard', [PerusahaanDashboardController::class, 'index'])->name('dashboard.index');

Route::get('profile', [PerusahaanProfileController::class, 'index'])->name('profile.index');
Route::put('profile', [PerusahaanProfileController::class, 'update'])->name('profile.update');
