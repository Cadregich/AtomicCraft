<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    private JWT $jwt;

    public function __construct(JWT $jwt) {
        $this->jwt = $jwt;
    }

    public function __invoke(Request $request)
    {
        $credentials = $this->validateCredentials($request);
        $remember = $request->input('remember');

        if (!$remember) {
            $this->jwt = new JWT(true);
        }

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = Auth::user();
        $user->tokens()->delete();

        return $this->jwt->login($user, ['Authorisation Success']);
    }

    private function validateCredentials(Request $request) {
        return $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
    }
}
