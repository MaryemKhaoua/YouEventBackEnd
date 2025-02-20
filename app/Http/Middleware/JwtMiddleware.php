<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token non fourni'], 401);
        }

        try {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), env('JWT_ALGO')));
            $user = User::find($decoded->id);

            if (!$user) {
                return response()->json(['error' => 'Utilisateur introuvable'], 401);
            }

            $request->merge(['user' => $user]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token invalide'], 401);
        }

        return $next($request);
    }
}
