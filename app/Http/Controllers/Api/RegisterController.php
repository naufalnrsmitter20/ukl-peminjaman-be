<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResources;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
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

            $user = User::create([
                "username" => $request->username,
                "password" => bcrypt($request->password)
            ]);
            return new ApiResources("success", "Success to add user", $user);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }
}