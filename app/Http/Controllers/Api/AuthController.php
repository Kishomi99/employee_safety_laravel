<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserInformation; 

class AuthController extends Controller
{
    public function login(Request $request)
    {
         try {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|in:Manager,Employee,Admin',
        ]);

       
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            if ($user->role !== $request->role) {
                return response()->json(['message' => 'Role mismatch'], 403);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong during login',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
