<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * ==================================================
     * 👤 PROFILE CONTROLLER
     * ==================================================
     */
    public function profile(Request $request)
    {
        try {
            $user = $request->user(); // 🔑 Sanctum authenticated user

            if (!$user) {
                return response()->json([
                    'message' => '❌ User not found',
                ], 404);
            }




            // 📤 JSON Response
            $response = [
                '_id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'avatar' => $user->avatar ?? '',
                'role' => $user->role,
                'addresses' => $user->addresses ?? [],
                'wish_list' => $user->wish_list ?? [],
                'cart' => $user->cart ?? [],
                'createdAt' => $user->created_at
                    ->timezone('Asia/Dhaka')
                    ->toDateTimeString(),
                'updatedAt' => $user->updated_at
                    ->timezone('Asia/Dhaka')
                    ->toDateTimeString(),
            ];


            Log::info('✅ User Profile', ['user_id' => $user->id, 'email' => $user->email]);

            return response()->json($response, 200);
        } catch (\Exception $err) {
            Log::error('❌ Error getting user profile', ['error' => $err->getMessage()]);
            return response()->json([
                'message' => '❌ Error getting user profile',
            ], 500);
        }
    }
}
