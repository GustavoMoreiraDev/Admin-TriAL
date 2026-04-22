<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'jwt.auth'   => \PHPOpenSourceSaver\JWTAuth\Http\Middleware\Authenticate::class,
            'jwt.refresh' => \PHPOpenSourceSaver\JWTAuth\Http\Middleware\RefreshToken::class,
            'role'       => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['message' => 'Token expirado.'], 401);
        });

        $exceptions->render(function (\PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['message' => 'Token inválido.'], 401);
        });

        $exceptions->render(function (\PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['message' => 'Token ausente.'], 401);
        });
    })->create();
