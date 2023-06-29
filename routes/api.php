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
    return Mod::all()->pluck('title');
});

//Route::get('/shop/paginator', function (Request $request) {
////    return $request->all();
//    $goods = Product::paginate(15)->onEachSide(0);
//    return response()->json($goods);
//});

Route::get('/shop/paginator', \App\Http\Controllers\Shop\ShopController::class);
