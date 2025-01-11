<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OrganizationController extends Controller
{
    public function addMember(Request $request)
    {
        // Sprawdzamy, czy zalogowany user jest adminem
        $authUser = $request->user();
        if ($authUser->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        $newUser = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'organization_id' => $authUser->organization_id, // ta sama org co admin
            'role' => 'member', // domyślnie
        ]);

        return response()->json(['message' => 'User created', 'user' => $newUser], 201);
    }
}
