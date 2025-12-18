<?php

use App\Http\Controllers\Api\GpsTrackingController;
use Illuminate\Support\Facades\Route;

Route::post('v1/submit-gps', [GpsTrackingController::class, 'submitGps'])->name('api.gps.submit');