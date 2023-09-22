<?php

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

Route::post('clear-auth-cookie', function () {
    return response('Cookies deleted')
        ->cookie('ait', null, -1)
        ->cookie('jwt', null, -1);
});

Route::namespace('App\Http\Controllers')->group(function () {
    Route::get('/user/{data}', 'UserController@getUserData')->middleware('auth:sanctum');

    Route::namespace('Auth')->group(function () {
        Route::post('/register', 'RegisterController');
        Route::post('/login', 'LoginController');
        Route::post('/logout', 'LogoutController')->middleware('auth:sanctum');
    });

    Route::middleware('auth:sanctum')->prefix('cabinet')->namespace('Cabinet')->group(function () {
        Route::get('/', 'CabinetController');
        Route::post('/pay', 'CabinetController@pay');
        Route::get('/user-info', 'CabinetController@getUserInfo');
        Route::post('/skin', 'PlayerAssetsController@upload');
        Route::delete('/skin', 'PlayerAssetsController@reset');
        Route::get('/common-currency-multiplier', 'CabinetController@getCommonCurrencyMultiplier');
        Route::post('/daily-gift', 'DailyGiftController')->middleware('session.user');
        Route::get('/assetPaths', 'CabinetController@getSkinAndCapePaths');
    });

    /*
     * For now, payment system servers cannot send callbacks to my api because the server is running locally.
     * Temporarily, payment data will be received from a link to which payment systems redirect the user
     * along with payment data. After uploading to the hosting, the system will be rebuilt
     */

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/callback/liqpay', 'PaymentCallbackController@Liqpay')->middleware('session.user');
        Route::post('/cabinet/liqpay', 'PaymentCallbackController@Liqpay')->middleware('session.user');
    });


    Route::prefix('shop')->namespace('Shop')->group(function () {
        Route::get('/goods-mods', function () {
            return Mod::orderBy('title', 'asc')->pluck('title');
        });
        Route::get('/products', 'ShopController');
        Route::post('/buy', 'BuyController')->middleware('auth:sanctum');;
        Route::get('/get-more-items', 'GetMoreItemsController')->middleware('auth:sanctum');
        Route::post('/create', 'ShopController@store')->name('goods-store');
    });

});

Route::get('/test', function (Request $request) {
    return $request->all();
});
