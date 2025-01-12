<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUser(Request $request)
    {
        $user = $request->user();
    
    
        // Pobierz użytkowników z tej samej organizacji (bez adminów)
        $users = User::where('organization_id', $user->organization_id)
                     ->where('role', '!=', 'admin')
                     ->get();
    
        return response()->json($users);
    }
}
