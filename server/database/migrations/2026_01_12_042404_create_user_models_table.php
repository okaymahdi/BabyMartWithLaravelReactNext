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
        Schema::create('user_models', function (Blueprint $table) {
            $table->id();

    $table->string('name', 50);
    $table->string('email')->unique();
    $table->string('password');

    $table->string('avatar')->nullable();
    $table->enum('role', ['user', 'admin', 'manager'])->default('user');
    $table->enum('gender', ['male', 'female', 'other'])->nullable();

    // JSON fields (Mongo style)
    $table->json('addresses')->nullable();
    $table->json('wish_list')->nullable();
    $table->json('cart')->nullable();

    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_models');
    }
};
