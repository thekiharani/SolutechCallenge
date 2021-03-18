<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
        return response()->json(["message" => "user account created", "user" => $user], Response::HTTP_CREATED);
    }

    // login
    public function login(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            "username" => "required|string",
            "password" => "required|string"
        ]);

        $user = User::where('email', $request->input('username'))
                ->orWhere('email', $request->input('username'))
                ->first();

        if ($user &&
            Hash::check($request->input('password'), $user->password)) {
            $user->tokens()->delete();
            $token = $user->createToken("personal-access")->plainTextToken;
            return response()->json([
                "access_token" => explode('|', $token)[1],
                "token_type" => "Bearer"
            ], Response::HTTP_OK);
        }
        return response()->json(["message" => "invalid credentials!"], Response::HTTP_UNAUTHORIZED);
    }

    // update
    public function update(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->user()->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ]);
    return response()->json(["message" => "user account updated"]);
    }

    // change password
    public function password_change(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required']
        ]);
        $user = $request->user();
        if (Hash::check($request->input('current_password'), $user->password)) {
            $user->update(['password' => Hash::make($request->input('new_password'))]);
            return response()->json(["message" => "password changed"], 200);
        }
        return response()->json(["message" => "current password is incorrect"], 428);
    }

    // Logout
    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->user()->tokens()->delete();
        return response()->json(["message" => "logout successful"]);
    }

    // Authenticated
    public function profile(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json($request->user());
    }

}
