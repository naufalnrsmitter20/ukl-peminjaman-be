<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ApiResources;
use App\Models\Borrow;
use App\Models\Inventory;
use App\Models\Returnh;
use Illuminate\Support\Facades\Validator;

class UsageReport extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "start_date" => "required|date",
            "end_date" => "required|date",
            "group_by" => "required|string",
        ]);

        if($validation->fails()){
            return new ApiResources(false, $validation->errors(), null);
        }
        $start = $request->start_date;
        $end = $request->end_date;
        $group_by = $request->group_by;

        $getItem = Inventory::where("category", $group_by)->get();
        $borrowpayload = Borrow::whereBetween("borrow_date", [$start, $end])->orWhereBetween("borrow_date", [$start, $end])->where()->count();
        $returnpayload = Returnh::whereBetween("return_date", [$start, $end])->orWhereBetween("return_date", [$start, $end])->count();
        $borrowAllidtotal = Borrow::whereBetween("borrow_date", [$start, $end])->orWhereBetween("borrow_date", [$start, $end])->pluck("id")->count();
        $borrowAllid = Borrow::whereBetween("borrow_date", [$start, $end])->orWhereBetween("borrow_date", [$start, $end])->pluck("id");
        $returnFiltered = Returnh::whereIn("borrow_id", $borrowAllid)->count();
        

        return new ApiResources("success", "success to create report!", [
            "analysis_period" => [
                "start_date" => $start,
                "end_date" => $end,
            ],
            "usage_analysis" => [
                "group" => $group_by,
                "total_borrowed" => $borrowpayload,
                "total_returned" => $returnpayload,
                "total_in_use" => $borrowAllidtotal - $returnFiltered
            ]
        ]);
    }
}