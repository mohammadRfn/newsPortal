<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    /**
     * Create a new class instance.
     */
    public function login($credentials)
    {
        if ($token = JWTAuth::attempt($credentials)) {
            return $token;
        }

        return null;
    }
    public function register($data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $token = JWTAuth::fromUser($user);
        return ['token' => $token, 'user' => $user];
    }
    public function logout($request)
    {
        $token = JWTAuth::getToken();
        JWTAuth::invalidate($token);
        return true;
    }
}
