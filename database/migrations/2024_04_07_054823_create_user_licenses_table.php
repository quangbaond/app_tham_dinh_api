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
        Schema::create('user_licenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('id_card')->nullable();
            $table->string('code')->nullable();
            $table->string('type')->nullable();
            $table->string('class')->nullable();
            $table->string('address')->nullable();
            $table->string('dob')->nullable();
            $table->string('name')->nullable();
            $table->string('date')->nullable();
            $table->string('place_issue')->nullable();
            $table->string('image_front')->nullable();
            $table->string('image_front_storage')->nullable();
            $table->string('image_back')->nullable();
            $table->string('image_back_storage')->nullable();
            $table->integer('point')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_licenses');
    }
};
