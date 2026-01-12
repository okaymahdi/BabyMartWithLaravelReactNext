<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        // ✅ validated data
        $data = $request->validated();

        // 🔒 Security: ignore role from client
        unset($data['role']);


        // ✅ Always set default role
        $data['role'] = 'user';

        // 🧾 Create user
        $user = User::create($data);

        // 🎟 Generate Sanctum token
        $token = $user->createToken('auth_token')->plainTextToken;

        // 📤 JSON response
        return response()->json([
            '_id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'role' => $user->role,
            'addresses' => $user->addresses ?? [],
            'wish_list' => $user->wish_list ?? [],
            'cart' => $user->cart ?? [],
            'token' => $token,
            'createdAt' => $user->created_at->timezone('Asia/Dhaka'),
            'updatedAt' => $user->updated_at->timezone('Asia/Dhaka'),
        ], 201);
    }
}