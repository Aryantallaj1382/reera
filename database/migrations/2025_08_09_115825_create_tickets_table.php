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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->enum('subject', ['support', 'technical', 'financial', 'other']);
            $table->enum('department', ['technical', 'financial', 'support', 'management']);

            $table->enum('status', ['open', 'pending', 'closed'])->default('open');

            $table->timestamps();
        });
        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade'); // ایدی تیکت
            $table->text('message'); // پیام
            $table->boolean('is_support')->default(false); // آیا پیام توسط پشتیبان ارسال شده؟

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
