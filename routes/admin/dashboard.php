<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardsController;
use App\Http\Controllers\Admin\ProfileController;

Route::get('dashboard', [DashboardsController::class, 'index'])->name('dashboard');

/******** Profile ********/
Route::get('profile', [ProfileController::class, 'index'])->name('admin.profile.index');
Route::put('profile', [ProfileController::class, 'update'])->name('admin.profile.update');
