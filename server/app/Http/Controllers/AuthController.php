<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function registerController(RegisterRequest $request): JsonResponse
    {
        $payload = $request->validated();

        // default role logic
        $allowedRoles = ['user', 'admin', 'manager'];
        $finalRole = 'user';
        if (isset($payload['role']) && in_array($payload['role'], $allowedRoles)) {
            $finalRole = $payload['role'];
        }
        $payload['role'] = $finalRole;

        try {
            $user = User::create($payload);

            // Generate JWT token (using Laravel Sanctum or tymon/jwt)
            $token = $user->createToken('api_token')->plainTextToken;

            return response()->json([
                '_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar ?? '',
                'role' => $user->role,
                'addresses' => $user->addresses ?? [],
                'token' => $token,
                'createdAt' => $user->created_at->timezone('Asia/Dhaka')->toDateTimeString(),
                'updatedAt' => $user->updated_at->timezone('Asia/Dhaka')->toDateTimeString(),
            ], 201);

        } catch (\Exception $error) {
            Log::error('Register error => ' . $error->getMessage());
            // For dev only: return full error
            return response()->json([
                'message' => $error->getMessage(),
                'trace' => $error->getTrace()
            ], 500);
        }
    }
}
