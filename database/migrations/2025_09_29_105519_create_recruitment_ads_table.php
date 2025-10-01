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
        Schema::create('recruitment_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // نام برند
            $table->timestamps();
        });

        Schema::create('recruitment_ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_id')->unique()->constrained('ads')->onDelete('cascade');
            $table->foreignId('languages_id')->unique()->constrained('languages')->onDelete('cascade');
            $table->foreignId('recruitment_categories_id')->unique()->constrained('recruitment_categories')->onDelete('cascade');
            $table->string('days')->nullable();
            $table->string('time')->nullable();
            $table->string('type')->nullable();
            $table->string('degree')->nullable();
            $table->json('details')->nullable();
            $table->json('skill')->nullable();
            $table->json('role')->nullable();
            $table->boolean('site_massage')->default(false);
            $table->boolean('my_phone')->default(false);
            $table->boolean('other_phone')->default(false);
            $table->string('other_phone_number')->nullable();
            $table->foreignId('currencies_id')->constrained('currencies')->onDelete('cascade');
            $table->string('price')->nullable();
            $table->string('donation')->nullable();
            $table->boolean('cash')->nullable()->default(false);
            $table->boolean('installments')->nullable()->default(false);
            $table->boolean('check')->nullable()->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recruitment_ads');
    }
};
