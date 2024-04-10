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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique();
            $table->tinyInteger('role')->default(0)->comment('0: user, 1: admin, 2: super admin, 3: customer service');
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('password');
            $table->string('ho_so_tin_dung')->nullable();
            $table->string('xep_hang_tin_dung')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
