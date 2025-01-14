<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    /**
     * Rejestracja: tworzy nową organizację oraz pierwszego użytkownika (admina).
     * Zwraca token Sanctum w formie 'access_token'.
     */
    public function register(Request $request)
    {
        // Walidacja danych wejściowych
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required','email','max:255', Rule::unique('users','email')],
            'password' => 'required|string|min:8',
            'organization_name' => 'required|string|max:255',
        ]);

        // Tworzenie organizacji
        $organization = Organization::create([
            'name' => $validatedData['organization_name'],
            // ewentualnie: 'address', 'phone', itp. jeśli masz takie pola
        ]);

        // Tworzenie usera (domyślnie admin)
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'organization_id' => $organization->id,
            'role' => 'admin', // pierwszy user w organizacji jest adminem
        ]);

        // Generowanie tokena (Laravel Sanctum)
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'user' => $user,
        ], 201);
    }

    /**
     * Logowanie: sprawdź email i hasło, utwórz nowy token.
     */
    public function login(Request $request)
    {
        // Walidacja
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        // Szukanie usera po email
        $user = User::where('email', $credentials['email'])->first();

        // Weryfikacja hasła
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Usuwanie starych tokenów i tworzenie nowych
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'user' => $user,
        ]);
    }

    /**
     * Wylogowanie: usuwanie tokenów aktualnego zapytania.
     * Działa tylko, jeśli user jest zalogowany (middleware auth:sanctum).
     */
    public function logout(Request $request)
    {
        // Usuwa bieżący token
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}
