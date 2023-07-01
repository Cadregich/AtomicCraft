<?php

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Mod;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('shop/goods-mods', function () {
    return Mod::orderBy('title', 'asc')->pluck('title');
});

Route::get('/shop/products', \App\Http\Controllers\Shop\ShopController::class);

Route::get('/test', function (Request $request) {
    return $request->all();
});
