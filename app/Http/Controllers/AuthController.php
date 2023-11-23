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
    /**
     * @OA\Post(
     *     path="/api/users/register",
     *     tags={"Users"},
     *     description="Register user",
     *     operationId="register",
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="name:",
     *                     description="Name of the user",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="email:",
     *                     description="Email of the user",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password:",
     *                     description="Password of the user",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User registered successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/users/login",
     *     tags={"Users"},
     *     description="Login user",
     *     operationId="login",
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="email:",
     *                     description="Email of the user",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password:",
     *                     description="Password of the user",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User logged in successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     */

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
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/users/logout",
     *     tags={"Users"},
     *     description="Logout user",
     *     operationId="logout",
     *     @OA\Parameter(
     *         name="api_key",
     *         in="header",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User logged out successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     */

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
