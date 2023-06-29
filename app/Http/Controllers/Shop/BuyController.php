<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\DataRequest;
use App\Services\Products\BuyProducts;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Token;


class BuyController extends Controller
{
    protected $buyGoodsService;

    public function __construct(BuyProducts $buyGoodsService)
    {
        $this->buyGoodsService = $buyGoodsService;
    }

    public function __invoke(DataRequest $request)
    {
        return ['id' => auth()->id()];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json(['error' => 'Не авторизован'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ошибка авторизации'], 401);
        }

        return response()->json(['user_id' => $user->id]);
        $requestData = $request->all();
        $purchaseDetails = $this->buyGoodsService->getPurchaseDetails($requestData);
        $buyCondition = $this->buyGoodsService->createPurchase($purchaseDetails);
//        return redirect()->route('shop')->with('status', $buyCondition);
        return $buyCondition;
    }
}
