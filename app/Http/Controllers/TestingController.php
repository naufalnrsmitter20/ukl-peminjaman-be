<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\ApiResources;

class TestingController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            // $user = User::latest()->get()->map(function($item){
            //     return Arr::except($item, ["id"]);
            // });
            $user = User::latest()->with("borrow")->get()->map(function($item){
                $values = collect(Arr::except($item->toArray(), ["id"]))->filter(function($h){
                    return strtoupper($h);
                });
                return $values;
            });

            // $filtered = Arr::except($user->toArray(), "username");
            return new ApiResources("success", "success", $user);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ]);
        }
    }
}