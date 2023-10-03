<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(StoreUserRequest $request)
    {
       $validatedData = $request->validated();
       
       $user = User::create([
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'password' => Hash::make($validatedData['password'])
       ]);

       $token = $user->createToken('API token of '.$user->name)->plainTextToken;

       return response()->json([
        'user' => $user->name,
        'token' => $token
       ], 201);
    }

    public function login(LoginUserRequest $request)
    {
        $validatedData = $request->validated();

        $credentials = [
            'email' => $validatedData['email'],
            'password' => $validatedData['password']
        ];

        if(!Auth::attempt($credentials)){
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();

        $token = $user->createToken('API token of '.$user->name)->plainTextToken;

        return response()->json([
            'user' => $user->name,
            'token' => $token
        ]);
    }

    public function logout()
    {
        return response()->json('Hello logout');
    }
}
