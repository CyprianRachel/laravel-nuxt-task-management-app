<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Endpointy dostępne tylko dla zalogowanych
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // testowe
    Route::get('/profile', function (Request $request) {
        return $request->user(); // zwraca dane aktualnie zalogowanego usera
    });
});

