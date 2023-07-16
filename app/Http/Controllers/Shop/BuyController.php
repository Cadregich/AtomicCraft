<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\DataRequest;
use App\Services\Products\BuyProducts;
use Illuminate\Http\Request;


class BuyController extends Controller
{
    protected $buyGoodsService;

    public function __construct(BuyProducts $buyGoodsService)
    {
        $this->buyGoodsService = $buyGoodsService;
    }

    public function __invoke(Request $request, DataRequest $dataRequest)
    {
        try {
            $user = $dataRequest->user();
            if (!$user) {
                return response()->json(['error' => 'Не авторизован'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ошибка авторизации'], 401);
        }

        $requestData = $dataRequest->all();
        $purchaseDetails = $this->buyGoodsService->getPurchaseDetails($requestData, $user->id);
        $this->buyGoodsService->createPurchase($purchaseDetails);
        return response('', 200);
    }
}
