<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    // 1. Rejestracja nowego użytkownika + organizacji
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => ['required','email','max:255',Rule::unique('users')],
            'password' => 'required|min:8',
            'organization_name' => 'required|string',
        ]);

        // Tworzymy nową organizację
        $organization = Organization::create([
            'name' => $validated['organization_name'],
            // ewentualnie address, phone...
        ]);

        // Tworzymy usera przypisanego do tej organizacji
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'organization_id' => $organization->id,
            'role' => 'admin' // np. pierwszy user w organizacji jest adminem
        ]);

        // Generujemy token dostępu (Sanctum)
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'user' => $user,
        ], 201);
    }

    // 2. Logowanie
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Usuwamy stare tokeny, tworzymy nowy
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'user' => $user,
        ]);
    }

    // 3. Wylogowanie
    public function logout(Request $request)
    {
        // Usuwa token, który aktualnie jest używany
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
