<?php

namespace App\Http\Middleware;

use App\Services\JWT;
use Closure;
use Illuminate\Http\Request;

class AddAuthToHeaderFromJwtCookie
{
    /*
     *  Get the JWT from the cookie and pass its value to the authorization header,
     *  thereby implementing the storage of the JWT in the httpOnly cookie.
     */

    public function handle(Request $request, Closure $next)
    {
        $jwt = new JWT;
        if (!$request->bearerToken()) {
            if ($request->hasCookie($jwt->tokenName)) {
                $token = $request->cookie($jwt->tokenName);

                $request->headers->add([
                    'Authorization' => 'Bearer ' . $token
                ]);
            }
        }

        return $next($request);
    }
}
