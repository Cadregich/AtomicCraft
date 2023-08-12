<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Services\Cabinet\PlayerAssetsService;
use Illuminate\Http\Request;

class PlayerAssetsController extends Controller
{
    protected PlayerAssetsService $userAssets;
    public string $type;

    public function __construct(Request $request, PlayerAssetsService $userAssets)
    {
        $this->type = $request->input('type');
        $this->userAssets = $userAssets;
    }

    /**
     * Uploading the skin or cape to the server.
     *
     * @returns bool | string
     */

    public function upload(Request $request): array|\Illuminate\Http\JsonResponse
    {
        $userName = $request->user()->name;
        $asset = $request->file('file');

        try {
            $this->userAssets->upload($asset, $this->type, $userName);
            return $this->userAssets->getSkinAndCapePaths($userName);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Asset upload error: ' . $e->getMessage(),
                'validSizes' => $this->userAssets->getAllValidSkinSizes($this->type)
            ], 413);
        }
    }

    /**
     * Resets skin or cape to default.
     *
     * @returns bool | string
     */

    public function reset(Request $request): array|string
    {
        $userName = $request->user()->name;
        $currentSkinPath = 'storage/player/skin/' . $userName . '.png';
        $capePath = 'storage/player/cape/' . $userName . '.png';
        $filePath = ($this->type === 'skin') ? $currentSkinPath : $capePath;
        try {
            $this->userAssets->remove($this->type, $filePath);
            return $this->userAssets->getSkinAndCapePaths($userName);
        } catch (\Exception $e) {
            return 'Asset reset error ' . $e;
        }
    }
}
