<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrganizationController;

// Publiczne (bez tokena)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Wymaga tokenu (auth:sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/organization/add-member', [OrganizationController::class, 'addMember']);

    // testowe: zwraca aktualnego usera
    Route::get('/profile', function (Request $request) {
        return $request->user();
    });
});

