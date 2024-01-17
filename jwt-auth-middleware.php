<?php

///*********** START  FILE ASSIGNGUARD MIDDLEWARE CLASS  *******************
namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AssignGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if($guard != null){
            auth()->shouldUse($guard);
            try {
                $user = JWTAuth::parseToken()->authenticate();
                return $next($request);
            } catch (Exception $e) {
                if ($e instanceof TokenInvalidException) {
                    return response()->json([
                        'code' => 401,
                        'status' => 'UNAUTHORIZED',
                        'success' => false,
                        'message' => 'Token is Invalid'
                    ],401);
                } else if ($e instanceof TokenExpiredException) {

                    return response()->json([
                        'code' => 401,
                        'status' => 'UNAUTHORIZED',
                        'success' => false,
                        'message' =>  'Token is Expired'
                    ],401);

                } else {

                    return response()->json([
                        'code' => 401,
                        'status' => 'UNAUTHORIZED',
                        'success' => false,
                        'message' =>  'Authorization Token not found'
                    ],401);

                }
            }
        }else{
            return response()->json([
                'code' => 401,
                'status' => 'UNAUTHORIZED',
                'success' => false,
                'message' =>  'Authorization Token not found'
            ],401);
        }
    }
}

///*********** END  FILE ASSIGNGUARD MIDDLEWARE CLASS  *******************



///*********** START  FILE JWTMIDDLWARE MIDDLEWARE CLASS  *******************

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtMiddleware extends BaseMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return response()->json(['status' => 'Token is Invalid']);
            } else if ($e instanceof TokenExpiredException) {
                return response()->json(['status' => 'Token is Expired']);
            } else {
                return response()->json(['status' => 'Authorization Token not found']);
            }
        }
        return $next($request);
    }
}

///*********** START  FILE JWTMIDDLWARE MIDDLEWARE CLASS  *******************


// App\Http\Kernel
protected $routeMiddleware = [
      ...
      'assign.guard' => \App\Http\Middleware\AssignGuard::class,
      'jwt.verify' => \App\Http\Middleware\JwtMiddleware::class,
]

// Route\Api.php
Route::get(...)->middleware('assign.guard:customer-api');
Route::get(...)->middleware('jwt.verify');

