<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResources;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = User::all();
            return new ApiResources("succcess", "payload user", $user);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = User::find($id);
            if(!$user){
                return new ApiResources(false, "user not found", $user);
            }
            return new ApiResources("succcess", "payload user by id", $user);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validation = Validator::make($request->all(), [
                "username" => "required|string",
                "password" => "required|string",
            ]);

            if($validation->fails()){
                return new ApiResources(false, $validation->errors(), null);
            }
            $checkId = User::find($id);
            if(!$checkId){
                return new ApiResources(false, "User not found", null);
            }

            $user = User::find($id)->update([
                "username" => $request->username,
                "password" => bcrypt($request->password)
            ]);
            return new ApiResources("success", "Success to update user", $user);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $checkId = User::find($id);
            if(!$checkId){
                return new ApiResources(false, "User not found", null);
            }
            $user = User::destroy($id);
            return new ApiResources("success", "Success to delete user", $user);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }
}