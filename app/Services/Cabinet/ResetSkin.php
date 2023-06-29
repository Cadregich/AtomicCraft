<?php

namespace App\Services\Cabinet;

use Exception;
use Illuminate\Support\Facades\File;

class ResetSkin
{
    /**
     * @throws Exception
     */

    public function __invoke($arr, $defaultSkinPath, $currentSkinPath, $capePath): bool
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
}
