<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/** 👤 Get current user (requires auth) */
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/** 📝 Register route */
Route::post('/auth/register', [RegisterController::class, 'register']);

/** 🔐 Login route */
Route::post('/auth/login', [LoginController::class, 'login']);

// 🔐 Protect route with Sanctum middleware
Route::middleware('auth:sanctum')->get('/auth/profile', [ProfileController::class, 'profile']);