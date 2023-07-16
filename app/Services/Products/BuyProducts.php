<?php

namespace App\Services\Products;


use App\Models\Product;
use App\Models\Purchase;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class BuyProducts
{
    public function getPurchaseDetails($requestData, $userId): array
    {
        $itemCount = intval($requestData['items-count']);
        $itemId = intval($requestData['item-id']);

        $this->validateCount($itemCount);

        $goods = Product::find($itemId);

        $this->validateGoodsId($goods, $itemId);

        return [
            'goods_id' => $goods->id,
            'user_id' => $userId,
            'goods_name' => $goods->name,
            'goods_count' => $itemCount,
            'purchase_price' => $goods->price * $itemCount
        ];
    }

    private function validateCount($goodsCount)
    {
        if (filter_var($goodsCount, FILTER_VALIDATE_INT) === false ||
            $goodsCount < 1 || $goodsCount > 999) {

            throw new InvalidArgumentException('Invalid goods count.');
        }
    }

    private function validateGoodsId($goods, $id)
    {
        if ($goods->id !== $id) {

            throw new InvalidArgumentException('Invalid goods id.');
        }
    }

    public function createPurchase($purchaseDetails)
    {
        try {
            DB::beginTransaction();
            Purchase::create($purchaseDetails);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
