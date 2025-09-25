<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary(); // آیدی ثابت
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('icon')->nullable(); // آیکون دسته‌بندی
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
