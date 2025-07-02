<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserInformation;

class UserController extends Controller
{
    public function getUserById($id)
{
    try {
        $user = User::with('userInformation')->find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
                        'reference_number'=> $user->reference_number,
            'information' => $user->userInformation
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'An error occurred while fetching the user.',
            'error' => $e->getMessage()
        ], 500);
    }
}
public function getUsersByRole($role)
{
    try {
        $validRoles = ['Employee', 'Manager'];

        if (!in_array($role, $validRoles)) {
            return response()->json(['message' => 'Invalid role'], 400);
        }

        $users = User::where('role', $role)
                     ->with('userInformation')
                     ->get();

        return response()->json($users, 200);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'An error occurred while fetching users.',
            'error' => $e->getMessage()
        ], 500);
    }
}


}
