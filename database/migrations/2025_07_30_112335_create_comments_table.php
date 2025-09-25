<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('body')->nullable(); // متن کامنت (اختیاری برای امتیاز بدون توضیح)
            $table->morphs('commentable'); // commentable_id و commentable_type
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // کامنت‌گذار

            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade'); // ریپلای
            $table->boolean('is_approved')->default(false); // وضعیت تایید

            // امتیازها از ۱ تا ۵
            $table->tinyInteger('owner_behavior_rating')->nullable(); // رفتار مالک
            $table->tinyInteger('price_clarity_rating')->nullable(); // شفافیت قیمت
            $table->tinyInteger('info_honesty_rating')->nullable(); // صداقت اطلاعات
            $table->tinyInteger('cleanliness_rating')->nullable(); // تمیزی خانه

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
