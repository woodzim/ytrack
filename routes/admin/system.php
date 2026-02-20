<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LogActivityController;

Route::get('/logs', [LogActivityController::class, 'index'])->name('logs.index');
