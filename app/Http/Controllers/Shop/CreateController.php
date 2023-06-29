<?php

namespace App\Http\Controllers\Shop;


use App\Http\Controllers\Controller;
use App\Models\Mod;

class CreateController extends Controller
{
    public function __invoke()
    {
        $mods = Mod::orderBy('title', 'asc')->get();
        return view('Shop/CreateGoods', compact('mods'));
    }
}
