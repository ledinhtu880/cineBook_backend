<?php

use App\Http\Controllers\CinemaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', 'logout');
    });
});

Route::controller(CinemaController::class)->prefix('cinemas')->group(function () {
    Route::get('/getAll', 'index');
});

Route::controller(MovieController::class)->prefix('movies')->group(function () {
    Route::get('/getAll', 'index');
});
