<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;

// Publiczne trasy (bez tokenu, dostępne dla każdego)
Route::post('/register', [AuthController::class, 'register']);  // Rejestracja użytkownika
Route::post('/login', [AuthController::class, 'login']);  // Logowanie użytkownika

// Trasy wymagające autoryzacji (token Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    // Wylogowanie użytkownika
    Route::post('/logout', [AuthController::class, 'logout']);

    // Trasy związane z organizacją
    Route::post('/organization/add-member', [OrganizationController::class, 'addMember']);

    // Trasy związane z zamówieniami
    Route::post('/orders/generate', [OrderController::class, 'generateOrders']);  // Generowanie zamówień
    Route::get('/orders', [OrderController::class, 'index']);  // Pobieranie zamówień

    // Trasa przypisania zadania użytkownikowi
    Route::post('/orders/{orderId}/assign-task', [TaskController::class, 'assignTask']);  // Przypisanie zadania do zamówienia

    // Trasy związane z zadaniami
    Route::get('/tasks', [TaskController::class, 'getTasksForUser']);  // Pobieranie zadań przypisanych do użytkownika

    // Trasy związane z użytkownikami
    Route::get('/users', [UserController::class, 'getUser']);  // Pobieranie użytkowników

    // Trasa do oznaczenia zadania jako zakończone
    Route::post('/tasks/{taskId}/mark-as-done', [TaskController::class, 'markAsDone']);

    // Trasa do zmiany statusu zamówienia na "closed"
    Route::put('/orders/{orderId}/close', [OrderController::class, 'closeOrder']);

    // Testowe: zwraca dane o aktualnym użytkowniku
    Route::get('/profile', function (Request $request) {
        return $request->user();  // Zwrócenie danych użytkownika z tokenem
    });
});
