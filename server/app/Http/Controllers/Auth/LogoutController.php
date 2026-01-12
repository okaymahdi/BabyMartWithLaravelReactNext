<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LogoutController extends Controller
{
    // ==================================================
    // 👋 LOGOUT CONTROLLER (SANCTUM)
    // ==================================================
    public function logout(Request $request)
    {
        try {
            $user = $request->user(); // 🔐 authenticated user

            if (!$user) {
                return response()->json([
                    'message' => '❌ Unauthorized',
                ], 401);
            }

            // 🔥 Revoke current access token
             $request->user()->currentAccessToken()->delete();

            Log::info('👋 User Logged Out Successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            return response()->json([
                'success' => true,
                'message' => '✅ User logged out successfully',
                'loggedOutAt' => Carbon::now()
                    ->timezone('Asia/Dhaka')
                    ->toDateTimeString(),
            ], 200);

        } catch (\Exception $err) {
            Log::error('❌ Logout error', [
                'error' => $err->getMessage(),
            ]);

            return response()->json([
                'message' => '❌ Logout failed',
            ], 500);
        }
    }
}
