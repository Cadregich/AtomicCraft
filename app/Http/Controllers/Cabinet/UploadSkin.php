<?php

namespace App\Http\Controllers\Cabinet;

use Exception;

class UploadSkin
{
    /**
     * @throws Exception
     */

    public function __invoke($request): bool
    {
        if ($request->hasFile('skin')) {
            $skin = $request->file('skin');
            $skin->storeAs('public/player', 'skin.png');
            return true;
        } elseif ($request->hasFile('cape')) {
            $cape = $request->file('cape');
            $cape->storeAs('public/player', 'cape.png');
            return true;
        } else {
            throw new Exception('Skin or cape is not recognized');
        }
    }
}
