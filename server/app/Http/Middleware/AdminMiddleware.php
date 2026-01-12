<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = $request->user(); // ✅ Sanctum way

            if ($user && in_array($user->role, ['admin', 'manager'])) {
            return $next($request);
        }

            return response()->json([
                'message' => '❌ Not authorized as admin/manager',
            ], 403);

        } catch (\Exception $e) {
            Log::error('❌ AdminMiddleware error', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => '❌ Something went wrong checking admin',
            ], 500);
        }
    }
}
