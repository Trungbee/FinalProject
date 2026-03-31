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
        Schema::create('posts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->text('content')->nullable();
        $table->string('location_name')->nullable();
        $table->decimal('lat', 10, 8)->nullable();
        $table->decimal('lng', 11, 8)->nullable();
        $table->enum('privacy', ['PUBLIC', 'FRIENDS', 'PRIVATE'])->default('PUBLIC');
        $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
