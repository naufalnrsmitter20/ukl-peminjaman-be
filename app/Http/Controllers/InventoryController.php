<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Http\Resources\ApiResources;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
{
    public function index()
    {
        try {
            $inventory = Inventory::all();
            return new ApiResources("succcess", "payload inventory", $inventory);
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
                "name" => "required|string",
                "category" => "required|string",
                "location" => "required|string",
                "quantity" => "required",
            ]);

            if($validation->fails()){
                return new ApiResources(false, $validation->errors(), null);
            }

            $inventory = Inventory::create([
                "name" => $request->name,
                "category" => $request->category,
                "location" => $request->location,
                "quantity" => $request->quantity,
            ]);
            return new ApiResources("success", "Success to add inventory", $inventory);
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
            $inventory = Inventory::find($id);
            if(!$inventory){
                return new ApiResources(false, "user not found", $inventory);
            }
            return new ApiResources("succcess", "payload inventory by id", $inventory);
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
                "name" => "required|string",
                "category" => "required|string",
                "location" => "required|string",
                "quantity" => "required",
            ]);

            if($validation->fails()){
                return new ApiResources(false, $validation->errors(), null);
            }
            $checkId = Inventory::find($id);
            if(!$checkId){
                return new ApiResources(false, "Inventory not found", null);
            }

            $inventory = Inventory::find($id)->update([
                "name" => $request->name,
                "category" => $request->category,
                "location" => $request->location,
                "quantity" => $request->quantity,
            ]);
            return new ApiResources("success", "Success to add inventory", $inventory);
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
            $checkId = Inventory::find($id);
            if(!$checkId){
                return new ApiResources(false, "Inventory not found", null);
            }
            $inventory = Inventory::destroy($id);
            return new ApiResources("success", "Success to delete inventory", $inventory);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }
}