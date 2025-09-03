<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Faker\Provider\ar_EG\Person;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email:rfc,dns|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('api-token', ['post:read', 'post:create'])->plainTextToken;

        return response()->json(['ok' => true, $user, 'token' => $token]);
    }

    function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email:rfc,dns',
            'password' => 'required|string|min:8',
        ]);

        if (Auth::attempt($validated)) {
            $user = User::where('email', $validated['email'])->firstOrFail(); 

            $token = $user->createToken('api-token', ['post:read', 'post:create'])->plainTextToken;

            return response()->json(['ok' => true, 'user' => $user, 'token' => $token]);
        }
        return response()->json(['ok' => false, 'message' => 'Invalid credentials'], 401);

    }

    function logout(Request $request)
    {
        $token = $request->bearerToken();

        if(!$token){
            return response()->json(['ok' => false, 'message' => 'Token not provided'], 400);
        }

        $acess_token = PersonalAccessToken::findToken($token);

        if(!$acess_token){
            return response()->json(['ok' => false, 'message' => 'Invalid token'], 400);
        }

        $acess_token->delete();

        return response()->json(['ok' => true, 'message' => 'Logged out successfully']);
    }
}
