<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
            try {
                $jwt = $request->bearerToken();

                $decoded = JWT::decode($jwt, new Key(env('JWT_SECRET_KEY'), 'HS256'));

                if ($decoded->role == 'user') {
                    return $next($request);
                } else {
                    return response()->json('Unauthorized', 401);
                }
            } catch (ExpiredException $e) {
                return response()->json($e->getMessage(), 400);
            }
    }
}
