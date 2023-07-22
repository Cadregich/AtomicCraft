<?php

namespace App\Services\Cabinet;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Exception;

class PlayerAssetsService
{
    /**
     * @throws Exception
     */

    public function upload($request, $userName): bool
    {
        if ($request->hasFile('skin')) {
            $skin = $request->file('skin');
            $skin->storeAs('public/player/skin', $userName . '.png');
            return true;
        } elseif ($request->hasFile('cape')) {
            $cape = $request->file('cape');
            $cape->storeAs('public/player/cape', $userName . '.png');
            return true;
        } else {
            throw new Exception('Asset is not recognized');
        }
    }

    /**
     * @throws Exception
     */

    public function remove($arr, $defaultSkinPath, $currentSkinPath, $capePath): bool
    {
        if (File::exists($defaultSkinPath)) {
            if ($arr['type'] === 'skin') {
                //File::delete($currentSkinPath);
                if (File::exists($currentSkinPath)) {
                    File::copy($defaultSkinPath, $currentSkinPath);
                    return true;
                }
            } elseif ($arr['type'] === 'cape') {
                if (File::exists($capePath)) {
                    File::delete($capePath);
                    return true;
                }
            } else {
                throw new Exception('Asset type error');
            }
        } else {
            throw new Exception('Server error, skin not fount att ' . $defaultSkinPath);
        }
        throw new Exception('Unknown error');
    }

    public function getSkinAndCapePaths($userName)
    {
        $skinPath = 'storage/player/defaultSkin.png';
        $capePath = '';
        $userSkinPath = 'storage/player/skin/' . $userName . '.png';
        $userCapePath = 'storage/player/cape/' . $userName . '.png';

        if (File::exists($userSkinPath)) {
            $skinPath = $userSkinPath;
        }
        if (File::exists($userCapePath)) {
            $capePath = $userCapePath;
        }
        return ['skinPath' => $skinPath, 'capePath' => $capePath];
    }
}
