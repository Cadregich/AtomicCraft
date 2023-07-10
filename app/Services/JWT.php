<?php

namespace App\Services;

/*
|--------------------------------------------------------------------------
|   JWT Authorisation
|--------------------------------------------------------------------------
|
|   Here are the methods for working with authorisation cookies.
|   When authorizing on the client, return the httpOnly JWT cookie and the usual
|   AIT cookie - Authentication Indicator Token, this token has the same validity
|   period as the jwt token but does not have httpOnly and does not carry data,
|   it will give the client the opportunity determine if the user is logged in
|
*/

class JWT
{
    /**
     *  Specifies the name of JWT
     */

    public string $tokenName = 'jwt';

    /**
     *  Specifies the name of AIT
     */

    public string $aitName = 'ait';

    /**
     * Specifies how long the cookie will be stored. If the value is null - cookies
     * will last as long as specified in the config sanctum - expiration,
     * if 'session' then the session, respectively.
     *
     * @var null|string
     */

    private mixed $authTime;

    /**
     * By default, the value of $authTime will be false, but if it is necessary
     * for cookies to last during the session, then it is necessary
     * to pass true when creating an instance.
     * This way you can implement the "remember me" functionality.
     *
     * @param bool $authSession
     */

    public function __construct(bool $authSession = false)
    {
        if (!$authSession) {
            $this->authTime = config('sanctum.expiration');
        } else {
            $this->authTime = null;
        }
    }

    public function createJWTCookie(string $jwtText)
    {
        $cookie = cookie(
            $this->tokenName,
            $jwtText,
            $this->authTime,
            null,
            null,
            false,
            true
        );

        if ($cookie === null) {
            throw new \InvalidArgumentException('Unable to create JWT cookie');
        }

        return $cookie;
    }

    public function createAITCookie()
    {
        $aitValue = ($this->authTime === null) ? 'session' : $this->authTime;

        return cookie(
            $this->aitName,
            encrypt($aitValue),
            $this->authTime,
            null,
            null,
            false,
            false
        );
    }

    /**
     * Return responce with jwt and ait cookies.
     *
     * @param mixed $user
     * @param array $responceData
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function login(mixed $user, array $responceData = [])
    {
        $jwt = $user->createToken($this->tokenName);
        $cookieJWT = $this->createJWTCookie($jwt->plainTextToken);
        $cookieAIT = $this->createAITCookie();

        return response()->json($responceData)->withCookie($cookieJWT)->withCookie($cookieAIT);
    }
}
