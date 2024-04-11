<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('longitude')->unique()->nullable();
            $table->string('latitude')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique();
            $table->tinyInteger('role')->default(0)->comment('0: user, 1: admin, 2: super admin, 3: service');
            $table->tinyInteger('status')->default(1)->comment('0: inactive, 1: active');
            $table->tinyInteger('status_1')->default(0)->comment('0: chưa thẩm định, 1: đã thẩm định');
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('password');
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
