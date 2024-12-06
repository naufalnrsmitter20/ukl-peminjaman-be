<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ApiResources;
use App\Models\Borrow;
use App\Models\Inventory;
use App\Models\Returnh;
use Illuminate\Support\Facades\Validator;

class BorrowAnalyst extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                "start_date" => "required|date",
                "end_date" => "required|date",
            ]);
    
            if($validation->fails()){
                return new ApiResources(false, $validation->errors(), null);
            }
            $start = $request->start_date;
            $end = $request->end_date;
            $inventory = Inventory::withCount(['borrow' => function ($query) use ($start, $end) {
                $query->whereBetween('borrow_date', [$start, $end]);
            }])->get();
            $inventorylate = Returnh::pluck("borrow_id");
            $findBorrowWithReturn = Borrow::find($inventorylate);

            function return_value($item){

            }
            
            return new ApiResources("success", "success to create report!", [
                "analysis_period" => [
                    "start_date" => $start,
                    "end_date" => $end,
                ],
                "frequently_borrowed_items" => $inventory->map(function($item) {
                    return [
                        "item_id" => $item->id,
                        "name" => $item->name,
                        "category" => $item->category,
                        "total_borrowed" => count($item->borrow)
                    ];
                }),
                "inefficient_items" => $inventory->map(function( $item)  {
                    return [
                        "item_id" => $item->id,
                        "name" => $item->name,
                        "category" => $item->category,
                        "total_borrowed" => count($item->borrow),
                        "total_late_return" => $item->borrow->map(function($br){
                            $exist = Returnh::where("borrow_id", $br->id)->get();
                            $filtered = $exist->filter(function($h){
                                return $h->return_date < $h->actual_return_date;
                            });
                            return count($filtered) === 0 ? count($filtered) : null;
                        }),
                    ];
                }),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }
}