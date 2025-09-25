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
        Schema::table('housing_ads', function (Blueprint $table) {
            $table->string('distance_from_shopping_center')->nullable()->after('text');
            $table->string('distance_from_taxi_stand')->nullable()->after('text');
            $table->string('distance_from_gas_station')->nullable()->after('text');
            $table->string('distance_from_hospital')->nullable()->after('text');
            $table->string('distance_from_bus_station')->nullable()->after('text');
            $table->string('distance_from_airport')->nullable()->after('text');
            $table->string('stage')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
