<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/debug-mail', function () {
    return [
        'default' => config('mail.default'),
        'host' => config('mail.mailers.smtp.host'),
        'port' => config('mail.mailers.smtp.port'),
        'username' => config('mail.mailers.smtp.username'),
        'encryption' => config('mail.mailers.smtp.encryption'),
        'env_host' => env('MAIL_HOST'),
    ];
});

Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->hasRole('perusahaan')) {
            return redirect()->route('perusahaan.dashboard.index');
        }
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

/******** Authentication ********/
Route::get('login', [LoginController::class, 'showLogin'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.store');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('register', [RegisterController::class, 'showRegister'])->name('register');
Route::post('register', [RegisterController::class, 'register'])->name('register.store');


Route::middleware(['auth'])->group(function () {
    // =================================================================
    // EMAIL VERIFICATION
    // =================================================================
    Route::get('/email/verify', function () {
        if (Auth::user()->hasVerifiedEmail()) {
            if (Auth::user()->hasRole('perusahaan')) {
                return redirect()->route('perusahaan.dashboard.index');
            }
            return redirect()->route('dashboard');
        }
        return view('pages.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        // Redirect based on role
        if ($request->user()->hasRole('perusahaan')) {
            return redirect()->route('perusahaan.dashboard.index');
        }
        return redirect()->route('dashboard');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Link verifikasi terkirim!');
    })->middleware(['throttle:6,1'])->name('verification.send');

    // =================================================================
    // ADMIN VALIDATION WAIT PAGE
    // =================================================================
    Route::get('/verification/wait', function (Request $request) {
        if ($request->user()->isValidated()) {
            if ($request->user()->hasRole('perusahaan')) {
                return redirect()->route('perusahaan.dashboard.index');
            }
            return redirect()->route('dashboard');
        }
        return view('pages.verification-wait');
    })->name('verification.wait');
});
