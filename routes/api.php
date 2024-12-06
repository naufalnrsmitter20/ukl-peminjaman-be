<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\BorrowAnalyst;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\TestingController;
use App\Http\Controllers\UsageReport;
use App\Http\Controllers\UserController;
use App\Http\Middleware\VerifyToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource("/user", UserController::class, [
    "user.index", "user.store", "user.show", "user.update", "user.destroy"
]);
Route::apiResource("/inventory", InventoryController::class, [
    "inventory.index", "inventory.store", "inventory.show", "inventory.update", "inventory.destroy"
]);

Route::post("/auth/register", RegisterController::class)->name("register");
Route::post("/auth/login", LoginController::class)->name("login");
Route::post("/auth/logout", LogoutController::class)->name("logout")->middleware(VerifyToken::class);

Route::post("/inventory/borrow", BorrowController::class)->middleware(VerifyToken::class);
Route::post("/inventory/return", ReturnController::class)->middleware(VerifyToken::class);
Route::post("/inventory/usage-report", UsageReport::class)->middleware(VerifyToken::class);
Route::post("/inventory/borrow-analysis", BorrowAnalyst::class)->middleware(VerifyToken::class);

Route::get("/testing", TestingController::class)->name("testing");