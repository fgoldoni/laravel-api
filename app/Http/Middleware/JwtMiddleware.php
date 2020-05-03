<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Token;

class JwtMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            if ($request->hasHeader('authorization')) {
                JWTAuth::parseToken()->authenticate();
                $rawToken = JWTAuth::getToken();
                $payload = JWTAuth::decode($rawToken);
                Auth::guard('web')->loginUsingId($payload['sub']);
            } elseif ($request->hasCookie('accessToken')) {
                $rawToken = $request->cookie('accessToken');
                $token = new Token($rawToken);
                Auth::loginUsingId(JWTAuth::decode($token)['sub']);
            }
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return response()->json(['message' => 'Token is Invalid'], IlluminateResponse::HTTP_UNAUTHORIZED);
            } elseif ($e instanceof TokenExpiredException) {
                return response()->json(['message' => 'Token is Expired'], IlluminateResponse::HTTP_UNAUTHORIZED);
            } elseif ($e instanceof TokenBlacklistedException) {
                return response()->json(['message' => 'Token is Blacklisted'], IlluminateResponse::HTTP_UNAUTHORIZED);
            } elseif ($e instanceof JWTException) {
                return response()->json(['message' => 'Need to Login Again'], IlluminateResponse::HTTP_UNAUTHORIZED);
            }

            return response()->json(['message' => 'Token missing or badly formatted'], IlluminateResponse::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
