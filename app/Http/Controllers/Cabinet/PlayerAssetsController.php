<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Services\Cabinet\PlayerAssetsService;
use Illuminate\Http\Request;

class PlayerAssetsController extends Controller
{
    protected $userAssets;

    public function __construct(PlayerAssetsService $userAssets)
    {
        $this->userAssets = $userAssets;
    }

    /**
     * Uploading the skin or cape to the server.
     *
     * @returns bool | string
     */

    public function upload(Request $request)
    {
        $userName = $request->user()->name;
        try {
            $this->userAssets->upload($request, $userName);
            return $this->userAssets->getSkinAndCapePaths($userName);
        } catch (\Exception $e) {
            return 'Asset upload error ' . $e;
        }
    }

    /**
     * Resets skin or cape to default.
     *
     * @returns bool | string
     */

    public function reset(Request $request)
    {
        $userName = $request->user()->name;
        $type = $request->input('type');
        $currentSkinPath = 'storage/player/skin/' . $userName . '.png';
        $capePath = 'storage/player/cape/' . $userName . '.png';
        try {
            $this->userAssets->remove($type, $currentSkinPath, $capePath);
            return $this->userAssets->getSkinAndCapePaths($userName);
        } catch (\Exception $e) {
            return 'Asset reset error ' . $e;
        }
    }
}
