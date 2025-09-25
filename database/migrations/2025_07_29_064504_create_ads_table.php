<?php

// database/migrations/xxxx_xx_xx_create_ads_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('category_id');
            $table->string('title');
            $table->enum('status', ['pending', 'approved', 'rejected', 'sold'])->default('pending');

            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('restrict');
        });
    }

    public function down(): void {
        Schema::dropIfExists('ads');
    }
};
