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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained('chats')->onDelete('cascade'); // ارتباط با چت
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade'); // فرستنده
            $table->text('message')->nullable(); // متن پیام
            $table->string('file')->nullable(); // اگر فایل فرستاده شد
            $table->boolean('is_seen')->default(false); // وضعیت دیده شدن
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
