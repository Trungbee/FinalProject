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
    Schema::table('users', function (Blueprint $table) {
        // Xóa cột cũ (nếu bạn đã tạo ở bước trước)
        if (Schema::hasColumn('users', 'is_premium')) {
            $table->dropColumn('is_premium');
        }
        // Thêm cột mới
        $table->string('premium_tier')->default('none');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('premium_tier');
    });
}
};
