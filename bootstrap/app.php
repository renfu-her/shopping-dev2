<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'restore.member.session' => \App\Http\Middleware\RestoreMemberSession::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            '/payment/result',
            '/payment/notify'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
