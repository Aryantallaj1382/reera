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


        Schema::create('housing_ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_id')->unique()->constrained('ads')->onDelete('cascade');
            $table->string('area')->nullable();
            $table->string('year')->nullable();
            $table->integer('number_of_bedrooms')->nullable();
            $table->string('number_of_bathroom')->nullable();
            $table->boolean('elevator')->nullable()->default(false);
            $table->boolean('parking')->nullable()->default(false);
            $table->boolean('furnished')->nullable()->default(false);
            $table->boolean('internet')->nullable()->default(false);
            $table->boolean('pet')->nullable()->default(false);
            $table->boolean('washing_machine')->nullable()->default(false);
            $table->boolean('balcony')->nullable()->default(false);
            $table->boolean('system')->nullable()->default(false);
            $table->boolean('empty')->nullable()->default(false);
            $table->boolean('in_use')->nullable()->default(false);
            $table->timestamp('visit_from');
            $table->text('text');
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
            $table->boolean('family')->nullable()->default(false);
            $table->boolean('woman')->nullable()->default(false);
            $table->boolean('man')->nullable()->default(false);
            $table->boolean('student')->nullable()->default(false);
            $table->json('rules')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('housing_ads');
    }
};
