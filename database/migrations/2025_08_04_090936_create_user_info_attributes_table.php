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
        Schema::create('user_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('resume_file')->nullable();         // فایل رزومه (مثلاً آدرس ذخیره فایل)
            $table->string('intro_video')->nullable();         // فایل ویدیو معرفی

            $table->enum('residency_status', [
                'permanent',    // اقامت دائم
                'student',      // دانشجو
                'asylum_seeker',// پناهنده
                'temporary',    // موقت
                'other'         // سایر
            ])->nullable();

            $table->unsignedBigInteger('min_salary')->nullable(); // حداقل حقوق
            $table->unsignedBigInteger('max_salary')->nullable(); // حداکثر حقوق

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_info_attributes');
    }
};
