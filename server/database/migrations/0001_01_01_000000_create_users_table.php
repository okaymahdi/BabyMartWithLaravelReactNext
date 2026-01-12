<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public function up(): void
    {
        // 👤 Users table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');                             // 📝 Name
            $table->string('username')->unique()->nullable();   // 🆔 Optional username
            $table->string('email')->unique();                  // ✉️ Email
            $table->timestamp('email_verified_at')->nullable();
            $table->string('avatar')->nullable();               // 🖼 Avatar
            $table->string('password');                         // 🔐 Password
            $table->enum('role', ['user', 'admin', 'manager'])->default('user'); // 👑 Role
            $table->enum('gender', ['male', 'female', 'other'])->nullable();     // 🚻 Gender
            $table->json('addresses')->nullable();              // 🏠 Optional addresses
            $table->json('wish_list')->nullable();             // ❤️ Optional wish list
            $table->json('cart')->nullable();                  // 🛒 Optional cart
            $table->rememberToken();                            // 🔑 Remember token
            $table->timestamps();
        });

        // 🔑 Password reset tokens
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

     /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};


  

