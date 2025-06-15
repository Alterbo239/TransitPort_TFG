<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\GestorMiddleware;
use App\Http\Middleware\AdministrativoMiddleware;
use App\Http\Middleware\OperadorMiddleware;
use App\Http\Middleware\ClienteMiddleware;
use Illuminate\Http\Middleware\HandleCors;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'gestor' => GestorMiddleware::class,
            'administrativo' => AdministrativoMiddleware::class,
            'operador' => OperadorMiddleware::class,
            'cliente' => ClienteMiddleware::class,
        ]);
        $middleware->prepend(HandleCors::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
