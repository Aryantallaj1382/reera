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
        Schema::create('vehicle_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // نام برند
            $table->timestamps();
        });
        Schema::create('vehicle_models', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // نام مدل
            $table->foreignId('brand_id')->constrained('vehicle_brands')->onDelete('cascade'); // ارتباط با برند
            $table->timestamps();
        });



        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_id')->unique()->constrained('ads')->onDelete('cascade');
            $table->foreignId('vehicle_brand_id')->constrained('vehicle_brands')->onDelete('cascade');
            $table->foreignId('vehicle_model_id')->constrained('vehicle_models')->onDelete('cascade');
            $table->string('function')->nullable();
            $table->string('gearbox')->nullable();
            $table->string('color')->nullable();
            $table->string('date_model')->nullable();
            $table->string('motor')->nullable();
            $table->string('chassis_status')->nullable();
            $table->string('body')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('text')->nullable();
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
        Schema::dropIfExists('vehicles');
    }
};
