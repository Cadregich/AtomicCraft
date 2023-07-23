<?php

namespace App\Services\Cabinet;

use Illuminate\Support\Facades\File;

class PlayerAssetsService
{
    /**
     * @throws \Exception
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
            throw new \Exception('Asset is not recognized');
        }
    }

    /**
     * @throws \Exception
     */

    public function remove($type, $currentSkinPath, $capePath): void
    {
        if ($type === 'skin') {
            if (File::exists($currentSkinPath)) {
                File::delete($currentSkinPath);
            } else {
                throw new \Exception('File ' . $currentSkinPath . ' not found');
            }
        } else if ($type === 'cape') {
            if (File::exists($capePath)) {
                File::delete($capePath);
            } else {
                throw new \Exception('File' . $capePath . 'not found');
            }
        } else {
            throw new \InvalidArgumentException('File' . $capePath . 'not found');
        }
    }

    public function getSkinAndCapePaths($userName): array
    {
        $defaultSkinPath = 'storage/player/defaultSkin.png';
        $capePath = '';
        $userSkinPath = 'storage/player/skin/' . $userName . '.png';
        $userCapePath = 'storage/player/cape/' . $userName . '.png';

        if (!File::exists($userSkinPath)) {
            $userSkinPath = $defaultSkinPath;
        }
        if (File::exists($userCapePath)) {
            $capePath = $userCapePath;
        }
        return ['skinPath' => $userSkinPath, 'capePath' => $capePath, 'defaultSkinPath' => $defaultSkinPath];
    }
}
