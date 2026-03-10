<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
        then: function () {
            Route::middleware(['web', 'auth', 'role:admin'])
                ->group(base_path('routes/admin/index.php'));

            Route::middleware(['web', 'auth', 'role:perusahaan', 'verified', 'validated'])
                ->prefix('perusahaan')
                ->name('perusahaan.')
                ->group(base_path('routes/perusahaan/index.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'validated' => \App\Http\Middleware\EnsureUserIsValidated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, \Illuminate\Http\Request $request) {
            // Jika user mengakses /dashboard tapi tidak punya hak aksesnya (karena khusus admin), alihkan ke dashboard perusahaannya.
            if ($request->is('dashboard') && auth()->check() && auth()->user()->hasRole('perusahaan')) {
                return redirect()->route('perusahaan.dashboard.index');
            }
            
            // Jika bukan kasus di atas, kembalikan response bawaan (403 default)
            return response()->view('errors.403', ['exception' => $e], 403);
        });
    })->create();
