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
        Schema::create('digital_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // نام برند
            $table->timestamps();
        });
        Schema::create('digital_models', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // نام مدل
            $table->foreignId('brand_id')->constrained('digital_brands')->onDelete('cascade'); // ارتباط با برند
            $table->timestamps();
        });
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('code', 10)->unique();
            $table->timestamps();
        });
        Schema::create('digital_ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_id')->unique()->constrained('ads')->onDelete('cascade');
            $table->foreignId('digital_brand_id')->constrained('digital_brands')->onDelete('cascade');
            $table->foreignId('digital_model_id')->constrained('digital_models')->onDelete('cascade');
            $table->enum('condition', ['new', 'almost_new', 'used', 'needs_repair'])->default('used');
            $table->string('view_time')->nullable();
            $table->boolean('phone_case')->nullable()->default(false);
            $table->boolean('glass')->nullable()->default(false);
            $table->boolean('stand')->nullable()->default(false);
            $table->boolean('cable')->nullable()->default(false);
            $table->text('text')->nullable();
            $table->boolean('site_massage')->default(false);
            $table->boolean('my_phone')->default(false);
            $table->boolean('other_phone')->default(false);
            $table->string('other_phone_number')->nullable();

            $table->foreignId('currencies_id')->constrained('currencies')->onDelete('cascade');
            $table->string('price')->nullable();
            $table->string('deposit')->nullable();
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
        Schema::dropIfExists('digital_ads');
    }
};
