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
        Schema::create('digital_currencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('bitcoin')->nullable();
            $table->string('ethereum')->nullable();
            $table->string('usdt')->nullable();
            $table->string('usdc')->nullable();
            $table->string('litecoin')->nullable();
            $table->string('bitcoin_cash')->nullable();
            $table->string('dogecoin')->nullable();
            $table->string('tron')->nullable();
            $table->string('cardano')->nullable();
            $table->string('polkadot')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_currencies');
    }
};
