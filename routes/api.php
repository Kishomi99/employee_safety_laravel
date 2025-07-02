<?php 
// routes/api.php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\UserController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });


Route::prefix('users')->group(function () {
    Route::get('/{id}', [UserController::class, 'getUserById']);            // /api/users/{id}
    Route::get('/role/{role}', [UserController::class, 'getUsersByRole']);  // /api/users/role/{role}
});
    // Add other protected routes here
});
