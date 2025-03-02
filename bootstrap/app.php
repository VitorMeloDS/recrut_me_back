<?php

use App\Http\Middleware\NotFoundMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use App\Http\Middleware\SanctumAuthMiddleware;
use App\Http\Middleware\CheckUserAccess;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        api: __DIR__.'/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append([
            NotFoundMiddleware::class,
            EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'auth.sanctum.custom' => SanctumAuthMiddleware::class,
            'check.user.access' => CheckUserAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
    })->create();