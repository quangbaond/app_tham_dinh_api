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
        Schema::create('user_finances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('thu_nhap_hang_thang')->nullable();
            $table->string('ten_cong_ty')->nullable();
            $table->string('dia_chi_cong_ty')->nullable();
            $table->string('so_dien_thoai_cong_ty')->nullable();
            $table->json('hinh_anh_sao_ke')->nullable();
            $table->string('point')->nullable();
            $table->boolean('check')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_finances');
    }
};
