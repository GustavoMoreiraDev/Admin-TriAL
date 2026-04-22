<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);

    Route::middleware('jwt.auth')->group(function () {
        Route::get('/me',             [AuthController::class, 'me']);
        Route::put('/profile',        [AuthController::class, 'updateProfile']);
        Route::post('/logout',        [AuthController::class, 'logout']);
    });
});

Route::middleware(['jwt.auth'])->prefix('usuarios')->group(function () {
    Route::get('/',          [UsuarioController::class, 'index'])  ->middleware('role:administrador,editor');
    Route::get('/{usuario}', [UsuarioController::class, 'show'])   ->middleware('role:administrador,editor');
    Route::post('/',         [UsuarioController::class, 'store'])  ->middleware('role:administrador');
    Route::put('/{usuario}', [UsuarioController::class, 'update']) ->middleware('role:administrador');
    Route::delete('/{usuario}', [UsuarioController::class, 'destroy'])->middleware('role:administrador');
});
