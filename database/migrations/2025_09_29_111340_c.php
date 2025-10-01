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
        Schema::table('recruitment_ads', function (Blueprint $table) {
            $table->enum('plan_type' , ['free' , 'vip'])->default('free')->nullable();
            $table->enum('pyment_status' , ['pending' , 'paid'])->default('pending')->nullable();
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
