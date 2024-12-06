<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResources;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                "username" => "required|string",
                "password" => "required|string",
            ]);
    
            if($validation->fails()){
                return new ApiResources(false, $validation->errors(), null);
            }
            
            $credentials = $request->only(["username", "password"]);
            
            if(!$token = auth()->guard("api")->attempt($credentials)){
                return new ApiResources(false, "Invalid username or Password", null);
            }
            return response()->json(["status" => "success", "message" => "Login Berhasil", "token" => $token]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }
}