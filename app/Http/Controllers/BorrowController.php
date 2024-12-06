<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Borrow;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Http\Resources\ApiResources;
use Illuminate\Support\Facades\Validator;

class BorrowController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                "user_id" => "required",
                "item_id" => "required",
                "borrow_date" => "required|date",
                "return_date" => "required|date",
            ]);

            if($validation->fails()){
                return new ApiResources(false, $validation->errors(), null);
            }
            $checkuserid = User::find($request->user_id);
            if(!$checkuserid){
                return new ApiResources(false, "User not found", null);
            }
            $checkinventoryid = Inventory::find($request->user_id);
            if(!$checkinventoryid){
                return new ApiResources(false, "Inventory not found", null);
            }
            
            $borrow = Borrow::create([
                "user_id" => $request->user_id,
                "inventory_id" => $request->item_id,
                "borrow_date" => $request->borrow_date,
                "return_date" => $request->return_date,
            ]);
            return new ApiResources("success", "Peminjaman berhasil dicatat!", $borrow);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }
}