<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateRequest;
use App\Http\Requests\GoodsSearchRequest;
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
        return ($this->buildGoodsQueryService)($request);
    }

    public function store(CreateRequest $request)
    {
        return ($this->storeGoodsService)($request);
    }
}
