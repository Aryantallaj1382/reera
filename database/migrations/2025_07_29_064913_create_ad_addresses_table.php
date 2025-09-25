<?php

// database/migrations/xxxx_xx_xx_create_ad_addresses_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ad_addresses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ad_id')->unique()->constrained('ads')->onDelete('cascade');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();

            $table->string('region')->nullable(); // منطقه یا محله
            $table->text('full_address')->nullable();

            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();

            $table->timestamps();

            $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');
        });
    }

    public function down(): void {
        Schema::dropIfExists('ad_addresses');
    }
};
