<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    // ==================================================
    // 🔐 LOGIN CONTROLLER
    // ==================================================
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated(); // ✅ validated data

        // 🔎 Find user by email
        $user = User::where('email', $credentials['email'])->first();

        // ❌ Invalid login check
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => '❌ Invalid email or password',
            ], 401);
        }

        // 🎟 Generate Sanctum token
        $token = $user->createToken('auth_token')->plainTextToken;

        // 🏠 Ensure addresses, wish_list, cart are arrays
        $addresses = is_array($user->addresses) ? $user->addresses : [];
        $wishList = is_array($user->wish_list) ? $user->wish_list : [];
        $cart = is_array($user->cart) ? $user->cart : [];

        // 📤 JSON response
        $response = [
            '_id' => $user->id,                        // 🆔 user ID
            'name' => $user->name,                     // 📝 name
            'username' => $user->username,             // 🆔 username
            'email' => $user->email,                   // 📧 email
            'avatar' => $user->avatar ?? '',           // 🖼 avatar
            'role' => $user->role,                     // 👑 role
            'addresses' => $addresses,                 // 🏠 addresses
            'wish_list' => $wishList,                  // ❤️ wish list
            'cart' => $cart,                            // 🛒 cart
            'token' => $token,                          // 🎟 JWT token
            'createdAt' => $user->created_at
                ->timezone('Asia/Dhaka')
                ->toDateTimeString(),                  // ⏰ created time (BDT)
            'updatedAt' => $user->updated_at
                ->timezone('Asia/Dhaka')
                ->toDateTimeString(),                  // 🛠 updated time (BDT)
        ];

        // 🐞 Log for debugging (optional)
        logger()->info('User Logged In Successfully', ['user_id' => $user->id]);

        return response()->json($response, 200);
    }
}
