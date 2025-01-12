<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class MembersController extends Controller
{
    public function getMembers(Request $request)
{
    $user = $request->user();


    // Pobierz użytkowników z tej samej organizacji (bez adminów)
    $users = User::where('organization_id', $user->organization_id)
                 ->where('role', '!=', 'admin')
                 ->get();

    return response()->json($users);
}
}
