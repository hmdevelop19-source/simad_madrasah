<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        /**
         * Daftarkan custom middleware dengan alias/nama pendek.
         * Alias 'role' memungkinkan penggunaan di route seperti:
         *   ->middleware('role:super_admin')
         *   ->middleware('role:guru,wali_kelas')
         *
         * Middleware 'auth' dan 'guest' sudah built-in di Laravel,
         * tidak perlu didaftarkan ulang di sini.
         */
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
