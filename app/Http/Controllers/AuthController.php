<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $login = Auth::attempt($request->only('email', 'password'));
        if (!$login) {
            return response()->json([
                'message' => 'Invalid Credentials',
            ]);
        }
        $user = Auth::user();
        
        // dd($user);
        $token = $user->createToken($request->device_name ?: 'web-api')->plainTextToken;
        
        return response()->json([
            'token' => $token,
        ]);
    }

    public function logout()
    {
        $user = Auth::user();
        $user->tokens->each(function (PersonalAccessToken $token) {
            $token->delete();
        });
        
        return response()->json([
            'message' => 'Successfully disconnected']);
    }
}
