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
        Schema::create('group_chats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_id')->nullable(); // با نوع مناسب
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade'); // فرستنده
            $table->text('message')->nullable(); // متن پیام

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_chats');
    }
};
