<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiLoginRequest;
use App\Http\Requests\ApiRegisterRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    protected $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }
    public function login(ApiLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $token = $this->authService->login($credentials);
        if ($token) {
            return response()->json([
                'token' => $token
            ]);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    public function register(ApiRegisterRequest $request)
    {
        $data = $request->only('name', 'email', 'password');

        $result = $this->authService->register($data);

        return response()->json($result, 201);

    }
    public function logout(Request $request)
    {
        $this->authService->logout($request);

        return response()->json(['message' => 'Successfully logged out'], 200);
    }
}
