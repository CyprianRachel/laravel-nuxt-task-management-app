<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OrganizationController extends Controller
{
    /**
     * Dodawanie nowego członka do organizacji.
     * Tylko zalogowany user z rolą 'admin' może to zrobić.
     */
    public function addMember(Request $request)
    {
        $authUser = $request->user();

        // Sprawdzamy rolę admin
        if ($authUser->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        // Walidacja danych nowego członka
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        // Tworzymy nowego usera w tej samej organizacji co admin
        $newUser = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'organization_id' => $authUser->organization_id,
            'role' => 'member',
        ]);

        // Zwracamy info w JSON
        return response()->json([
            'message' => 'User created',
            'user' => $newUser,
        ], 201);
    }
}
