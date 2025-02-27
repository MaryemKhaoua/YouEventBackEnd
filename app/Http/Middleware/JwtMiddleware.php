<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token non fourni'], 401);
        }

        try {
            $decodedToken = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
        } catch (\Exception $e) {
            Log::error("Erreur de décodage du token: " . $e->getMessage());
            return response()->json(['error' => 'Token invalide ou expiré'], 401);
        }

        $user = User::find($decodedToken->id);

        if (!$user) {
            Log::error("Erreur dans: ");
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        $request->attributes->set('user', $user);

        return $next($request);
    }
}
