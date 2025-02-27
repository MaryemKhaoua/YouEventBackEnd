<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ]);
    
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role_id' => 2,
        ]);
    
        return response()->json([
            'message' => 'User successfully created',
            'user' => $user
        ], 201);
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid email or password'], 401);
        }
    
        $payload = [
            'iss' => request()->getHost(),
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24),
            'id' => $user->id,
            'role' => $user->role_id,
        ];
    
        $jwt_secret = env('JWT_SECRET', 'default_secret_key');
        $jwt = JWT::encode($payload, $jwt_secret, 'HS256');
    
        return response()->json([
            'token' => $jwt,
            'user' => $user
        ]);
    }
    
    public function logout()
    {
        return response()->json(['message' => 'Logout successful'])->cookie(Cookie::forget('jwt_token'));
    }

    public function sendemail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $token = Str::random(60);
        User::where('email', $request->email)->update(['remember_token' => $token]);

        $resetLink = route('resetwithemail', ['token' => $token]);

        Mail::raw('Reset your password by clicking this link: ' . $resetLink, function ($message) use ($request) {
            $message->to($request->email)->subject('Password Reset');
        });

        return response()->json(['message' => 'Reset link sent']);
    }

    public function addpassword(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'token' => 'required',
        ]);

        User::where('remember_token', $request->token)->update([
            'password' => Hash::make($request->password),
            'remember_token' => null,
        ]);

        return response()->json(['message' => 'Password updated successfully']);
    }

    public function me(Request $request)
    {
        $jwt_secret = env('JWT_SECRET', 'default_secret_key');

        $token = $request->bearerToken();
    
        if (!$token) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        try {
            $decoded = JWT::decode($token, new Key($jwt_secret, 'HS256'));

            $user = User::find($decoded->id);

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            return response()->json(['user' => $user]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
    }
}