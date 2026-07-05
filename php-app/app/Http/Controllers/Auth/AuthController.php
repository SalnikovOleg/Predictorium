<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\UserResource;
use App\Services\Auth\CustomerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthController extends Controller
{
    public function __construct(
        protected CustomerService $service,
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $customer = $this->service->register($request->validated());

        $token = $customer->createToken('auth-token')->plainTextToken;

        return (new UserResource($customer))
            ->additional([
                'status' => true,
                'message' => 'Customer registered successfully',
                'token' => $token,
            ])
            ->response()
            ->setStatusCode(201);
    }

    public function login(LoginRequest $request): JsonResponse|JsonResource
    {
        $result = $this->service->login($request->validated());

        if (! $result) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        return (new UserResource($result['customer']))
            ->additional([
                'status' => true,
                'message' => 'Login successful',
                'token' => $result['token'],
            ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    public function me(Request $request): UserResource
    {
        return new UserResource($request->user());
    }
}
