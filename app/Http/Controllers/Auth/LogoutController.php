<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\JWT;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    private $jwt;

    public function __construct(JWT $jwt)
    {
        $this->jwt = $jwt;
    }

    public function __invoke(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        $cookieJWT = cookie()->forget($this->jwt->tokenName);
        $cookieAIT = cookie()->forget($this->jwt->aitName);


        return response()->json('Logout success')->withCookie($cookieJWT)->withCookie($cookieAIT);
    }
}
