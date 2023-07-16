<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getUserData(Request $request, $data) {
        if ($data)  {
            if ($data === 'data') {
                return [$request->user()];
            } else {
                return $request->user()->$data;
            }
        }

        throw new \http\Exception\InvalidArgumentException('Invalid user data');
    }
}
