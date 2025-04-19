<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $credentials = $request->safe()->only(['name', 'email', 'password']);
        $credentials['password'] = bcrypt($credentials['password']);
        User::create($credentials);
        return response()->noContent();
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            session()->regenerate();
            return response()->json(Auth::user());
        }

        return response()->json([
            'message' => 'Email or password is invalid'
        ], 401);
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return response()->noContent();
    }
}
