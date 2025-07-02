<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserInformation;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function register(Request $request)
    {
    try {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:Manager,Employee,Admin',
            'position' => 'required|string',
            'current_workplace' => 'required|string',
            'mobile_number' => 'required|digits_between:10,15|regex:/^[0-9]+$/',
            'gender' => 'required|in:Male,Female,Other',
            'profile_photo' => 'nullable|image|max:2048'
        ]);
//reference_number
        // Generate unique reference number
     $lastUser = User::where('reference_number', 'like', 'RFN%')
                ->orderByDesc('id')
                ->first();

$lastNumber = 0;

if ($lastUser && preg_match('/RFN(\d+)/', $lastUser->reference_number, $matches)) {
    $lastNumber = (int) $matches[1];
}

$newNumber = $lastNumber + 1;
$referenceNumber = 'RFN' . str_pad($newNumber, 3, '0', STR_PAD_LEFT); 
            // Create User
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'reference_number' => $referenceNumber,
                
            ]);

            // Handle profile photo
            $photoPath = null;
            if ($request->hasFile('profile_photo')) {
                $photoPath = $request->file('profile_photo')->store('profiles', 'public');
            }

            // Create UserInformation record
            //$userInfo = UserInformation::create([
            UserInformation::create([
                'user_id' => $user->id,
                'position' => $request->position,
                'current_workplace' => $request->current_workplace,
                'mobile_number' => $request->mobile_number,
                'gender' => $request->gender,
                'profile_photo' => $photoPath,
            ]);
// Load relationship
      //  $user->load('userInformation');


            return response()->json([
                'message' => 'User registered successfully',
               // 'user ' => $user
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
