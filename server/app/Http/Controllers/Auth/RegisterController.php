<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    // ==================================================
    // 👤 REGISTER CONTROLLER
    // ==================================================
    public function register(RegisterRequest $request): JsonResponse
    {
        // ✅ validated data
        $data = $request->validated();

        // --------------------------
        // 👑 Role logic
        // --------------------------
        // Admin can assign any role
        $allowedRoles = ['user', 'admin', 'manager'];

        // Check if role is valid and allowed
        if (isset($data['role']) && in_array($data['role'], $allowedRoles)) {
            $finalRole = $data['role'];
        } else {
            $finalRole = 'user'; // 🟢 default for normal user
        }

        $data['role'] = $finalRole;

        // --------------------------
        // 🧾 Create user
        // --------------------------
        $user = User::create($data);

        // --------------------------
        // 🎟 Generate Sanctum token
        // --------------------------
        $token = $user->createToken('auth_token')->plainTextToken;

        // --------------------------
        // 📤 JSON response
        // --------------------------
        return response()->json([
            '_id' => $user->id,                       // 🆔 MongoDB ID
            'name' => $user->name,                     // 📝 name
            'username' => $user->username,             // 🆔 username
            'email' => $user->email,                   // 📧 email
            'avatar' => $user->avatar ?? '',           // 🖼 avatar
            'role' => $user->role,                     // 👑 role
            'addresses' => $user->addresses ?? [],     // 🏠 addresses
            'wish_list' => $user->wish_list ?? [],     // ❤️ wish list
            'cart' => $user->cart ?? [],               // 🛒 cart
            'token' => $token,                         // 🎟 JWT token
            'createdAt' => $user->created_at
                ->timezone('Asia/Dhaka')
                ->toDateTimeString(),                 // ⏰ created time
            'updatedAt' => $user->updated_at
                ->timezone('Asia/Dhaka')
                ->toDateTimeString(),                 // 🛠 updated time
        ], 201);
    }
}
