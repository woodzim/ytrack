<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes Loader
|--------------------------------------------------------------------------
|
| Memuat semua file route terpisah untuk kerapian.
| Validated by: web, auth, role:admin
|
*/

require __DIR__ . '/dashboard.php';
require __DIR__ . '/users.php';
require __DIR__ . '/master-data.php';
require __DIR__ . '/assets.php';
require __DIR__ . '/tracking.php';
require __DIR__ . '/system.php';
