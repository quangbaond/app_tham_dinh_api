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
        Schema::create('user_history_loan_amounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_loan_amount_id')->constrained()->onDelete('cascade');
            $table->dateTime('ngay_tra');
            $table->bigInteger('so_tien_tra')->nullable();
            $table->bigInteger('so_goc_con_no')->nullable();
            $table->bigInteger('so_tien_lai')->nullable();
            $table->bigInteger('tong_goc_lai')->nullable();
            $table->integer('status')->comment('0: chưa trả, 1: đã trả')->default(0);
            $table->string('status_1')->comment('0: Đúng ngày, 1: Trả chậm')->default(0);
            $table->string('status_2')->comment('0: Đúng số tiền, 1: Trả thiếu, 2: Trả thừa')->default(0);
            $table->string('status_3')->comment('0: Chưa xác nhận, 1: Đã xác nhận')->default(0);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_history_loan_amounts');
    }
};
