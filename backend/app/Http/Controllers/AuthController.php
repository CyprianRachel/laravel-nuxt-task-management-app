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

        // Tworzymy organizację
        $organization = Organization::create([
            'name' => $validatedData['organization_name'],
            // ewentualnie: 'address', 'phone', itp. jeśli masz takie pola
        ]);

        // Tworzymy usera (domyślnie admin)
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'organization_id' => $organization->id,
            'role' => 'admin', // pierwszy user w organizacji jest adminem
        ]);

        // Generujemy token (Laravel Sanctum)
        $token = $user->createToken('auth_token')->plainTextToken;

        // Zwracamy w formacie JSON
        return response()->json([
            'access_token' => $token,
            'user' => $user,
        ], 201);
    }

    /**
     * Logowanie: sprawdza email i hasło, tworzy nowy token.
     */
    public function login(Request $request)
    {
        // Walidacja
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        // Szukamy usera po email
        $user = User::where('email', $credentials['email'])->first();

        // Weryfikacja hasła
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Usuwamy stare tokeny (opcjonalnie) i tworzymy nowy
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'user' => $user,
        ]);
    }

    /**
     * Wylogowanie: usuwa token aktualnego zapytania.
     * Działa tylko, jeśli user jest zalogowany (middleware auth:sanctum).
     */
    public function logout(Request $request)
    {
        // Usuwa bieżący token
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}
