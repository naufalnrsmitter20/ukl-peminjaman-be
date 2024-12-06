<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ApiResources;
use App\Models\Borrow;
use App\Models\Returnh;
use Illuminate\Support\Facades\Validator;

class ReturnController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                "borrow_id" => "required",
                "return_date" => "required|date",
            ]);

            if($validation->fails()){
                return new ApiResources(false, $validation->errors(), null);
            }
            $checkborrowid = Borrow::find($request->borrow_id);
            if(!$checkborrowid){
                return new ApiResources(false, "Borrow id not found", null);
            }
           
            $findborrowpayload = Borrow::find($request->borrow_id);

            $data = Returnh::create([
                "borrow_id" => $request->borrow_id,
                "return_date" => $request->return_date,
                "actual_return_date" => $findborrowpayload->return_date,
            ]);
            if(!$data){
                return new ApiResources(false, "Failed to return", null);
            }
            return new ApiResources("success", "Pengembalian berhasil dicatat!", [
                "borrow_id" => $request->borrow_id,
                "item_id" => $findborrowpayload->inventory_id,
                "user_id" => $findborrowpayload->user_id,
                "actual_return_date" => $findborrowpayload->return_date
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }
}