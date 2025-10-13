<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ads_reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reporter_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('ad_id')->constrained('ads')->onDelete('cascade');
            $table->string('reason')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();

        });
    }

    public function down(): void
    {
//
    }
};
