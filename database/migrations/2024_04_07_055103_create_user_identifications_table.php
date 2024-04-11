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
        Schema::create('user_identifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('id_card')->nullable();
            $table->string('address')->nullable();
            $table->string('address_now')->nullable();
            $table->string('birthday')->nullable();
            $table->string('name')->nullable();
            $table->string('features')->nullable();
            $table->string('sex')->nullable();
            $table->string('nationality')->nullable();
            $table->string('religion')->nullable();
            $table->string('doe')->nullable();
            $table->string('issue_date')->nullable();
            $table->string('image_front')->nullable();
            $table->string('image_back')->nullable();
            $table->string('facebook')->nullable();
            $table->string('zalo')->nullable();
            $table->string('msbhxh')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0: Chưa thẩm định, 1: Đã thẩm định');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_identifications');
    }
};
