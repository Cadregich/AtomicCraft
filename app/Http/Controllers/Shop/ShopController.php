<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateRequest;
use App\Http\Requests\GoodsSearchRequest;
use App\Models\Mod;
use App\Services\Products\BuildProductsQuery;
use App\Services\Products\StoreProducts;

class ShopController extends Controller
{
    protected $buildGoodsQueryService;
    protected $storeGoodsService;

    public function __construct(BuildProductsQuery $buildGoodsQueryService, StoreProducts $storeGoodsService)
    {
        $this->buildGoodsQueryService = $buildGoodsQueryService;
        $this->storeGoodsService = $storeGoodsService;
    }

    public function __invoke(GoodsSearchRequest $request)
    {
        $goods = ($this->buildGoodsQueryService)($request);
        $mods = Mod::orderBy('title', 'asc')->pluck('title');
//        return view('Shop/Shop', compact('goods', 'mods'));
        return $goods;
    }

    public function store(CreateRequest $request)
    {
        $storeCondition = ($this->storeGoodsService)($request);
        return redirect()->route('goods-create')->with('status', $storeCondition);
    }
}
