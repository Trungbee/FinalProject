<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('bookings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Ai đặt?
        $table->string('destination'); // Đi đâu?
        $table->date('check_in_date'); // Ngày đi
        $table->date('check_out_date')->nullable(); // Ngày về (có thể null nếu là tour đi về trong ngày)
        $table->integer('guests')->default(1); // Số người
        $table->decimal('total_price', 15, 2)->nullable(); // Tổng tiền
        $table->string('status')->default('pending'); // Trạng thái: pending, confirmed, cancelled
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
