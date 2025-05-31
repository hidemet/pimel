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
    ->withMiddleware(function (Middleware $middleware) {
         // Aggiungi il tuo alias qui:
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class, // Il tuo middleware esistente
            'redirect.admin' => \App\Http\Middleware\RedirectAdminFromUserDashboard::class, // Il tuo nuovo middleware
            // Mantieni gli altri alias che potrebbero essere già presenti
        ]);
        //
    })

    
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
