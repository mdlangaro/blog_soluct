<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function create(StoreUserRequest $request) 
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);
        Auth::attempt($request->only('email', 'password'));
        $user['token'] = $user->createToken($request->device_name ?: 'web-api')->plainTextToken;

        return response()->json([
            'data' => $user
        ], 200);

    }

    public function destroy(User $user)
    {
        if (Auth::user()->fladmin == 'Y') {
            $user->delete();
            
            return response()->json([
                'data' => 'Success'
            ], 200);
        }

        return response()->json([
            'data' => 'You are not able to delete users'
        ], 403);
    }
}
