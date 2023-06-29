<?php

namespace App\Services\Products;

use App\Models\Product;
use App\Models\Mod;

class BuildProductsQuery
{
    public function __invoke($request)
    {
        $searchQuery = $request->has('search') ? $request->validated()['search'] : false;
        $modQuery = $request->has('mod') ? $request->validated()['mod'] : false;

        $goodsHasQueries = $this->validateQueries($searchQuery, $modQuery);

        if (!$goodsHasQueries) {
            $goods = Product::join('mods', 'goods.mod_id', '=', 'mods.id')
                ->select('goods.id', 'goods.name', 'mods.title as mod', 'goods.img', 'goods.price')
                ->orderBy('mods.title', 'asc')->paginate(15)->onEachSide(0)->withQueryString();
        } else {
            $goods = $goodsHasQueries->join('mods','goods.mod_id', '=', 'mods.id')
                ->orderBy('mods.title', 'asc')
                ->select('goods.id', 'goods.name', 'mods.title as mod', 'goods.img', 'goods.price')
                ->paginate(15)->onEachSide(0)->withQueryString();
        }
        return $goods;
    }

    private function searchHandler($searchQuery)
    {
        if ($searchQuery) {
            return Product::where(function ($query) use ($searchQuery) {
                $query->where('name', 'like', '%' . $searchQuery . '%')
                    ->orWhereHas('associations', function ($query) use ($searchQuery) {
                        $query->where('title', 'like', '%' . $searchQuery . '%');
                    });
            });
        } else {
            return false;
        }
    }

    private function validateQueries($searchQuery, $modQuery)
    {
        $goodsSearch = $this->searchHandler($searchQuery);
        $modId = $modQuery ? Mod::where('title', $modQuery)->first()->id : false;

        if ($searchQuery && $modQuery) {
            $goods = $goodsSearch->where('mod_id', $modId);
        } elseif ($searchQuery && !$modQuery) {
            $goods = $goodsSearch;
        } elseif ($modQuery && !$searchQuery) {
            $goods = Product::where('mod_id', $modId);
        } else {
            return false;
        }

        if ($modQuery) {
            $goods->orderBy('name');
        }
        return $goods;
    }
}
