<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Services\Cabinet\ResetSkin;
use App\Services\Cabinet\UploadSkin;
use Illuminate\Http\Request;

class PlayerAssetsController extends Controller
{
    protected $uploadSkinService;
    protected $resetSkinService;

    public function __construct(UploadSkin $uploadSkinService, ResetSkin $resetSkinService)
    {
        $this->uploadSkinService = $uploadSkinService;
        $this->resetSkinService = $resetSkinService;
    }

    /**
     * Uploading the skin or cape to the server.
     *
     * @returns bool | string
     */

    public function upload(Request $request)
    {
        try {
            return ($this->uploadSkinService)($request);
        } catch (\Exception $e) {
            return 'Skin or cape upload error ' . $e;
        }
    }

    /**
     * Resets skin or cape to default.
     *
     * @returns bool | string
     */

    public function reset(Request $request)
    {
        $decodeData = $request->json()->all();
        $defaultSkinPath = storage_path('app/public/defaultSkin.png');
        $currentSkinPath = public_path('storage/player/skin.png');
        $capePath = public_path('storage/player/cape.png');
        try {
            return ($this->resetSkinService)($decodeData, $defaultSkinPath, $currentSkinPath, $capePath);
        } catch (\Exception $e) {
            return 'Skin or cape reset error ' . $e;
        }
    }
}
