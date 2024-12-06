<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Resources\ApiResources;
use Symfony\Component\HttpFoundation\Response;

class VerifyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->guard("api")->user() == null){
            return response()->json(
                [
                    "status" => false,
                    "message" => "Invalid Token",
                    "data" => null
                ], 401
                );
        }
        $token = $request->header("authorization");
        if(!$token) {
            return response()->json([
                "status" => false,
                "message" => "Token not provided"
            ], 401);
        }
        return $next($request);
    }
}