<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!auth()->attempt($credentials)) {
            abort(401, 'Invalid credentials!');
        }

        $token = auth()->user()->createToken('access_token');
        return response()->json([
            'data' => [
                'token' => $token->plainTextToken
            ]
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
    }
}
