<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UsersController;

/******** Pengguna ********/
Route::get('users', [UsersController::class, 'index'])->name('users.index');

Route::put('users/{id}/validate', [UsersController::class, 'validateUser'])->name('users.validate');
Route::put('users/{id}/reject', [UsersController::class, 'rejectUser'])->name('users.reject');
