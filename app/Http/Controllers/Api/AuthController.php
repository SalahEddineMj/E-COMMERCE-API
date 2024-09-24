<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register (RegisterRequest $request) {
        $data = $request->validated();
        $adminCount = User::where('role', 'admin')->count();
        $role = $adminCount < 2 ? 'admin' : 'user';
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $role
        ]);
        $token = $user->createToken('e-commerce')->plainTextToken;
        return response()->json(['message' => 'User created successfully',
        'user' => $user,
        'token' => $token
        ], 201);
    }

    public function signIn(LoginRequest $request) {
        $credentials = $request->validated();
        if(!Auth::attempt($credentials)) {
            return response([
                'message' => 'Provided email or password is incorrect'
            ], 401);
        }
        $user = Auth::user();
        $token = $user->createToken('e-commerce')->plainTextToken;
        return response()->json(['user' => new UserResource($user), 'token' => $token], 200);
    }

    public function logout(Request $request) {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }


    public function adminLogin(LoginRequest $request) {
        // Validate the login credentials
        $credentials = $request->validated();

        // Attempt to authenticate the user
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Get the authenticated user
        $user = Auth::user();

        // Ensure the user is an admin
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized access. Admins only.'], 403);
        }

        // Generate a token for the admin user
        $token = $user->createToken('admin')->plainTextToken;

        // Return the admin user data along with the token
        return response()->json([
            'user' => new UserResource($user),
            'token' => $token
        ], 200);
    }
}



