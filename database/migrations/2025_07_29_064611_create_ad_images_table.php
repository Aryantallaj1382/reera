<?php

// database/migrations/xxxx_xx_xx_create_ad_images_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ad_images', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ad_id')->constrained('ads')->onDelete('cascade');
            $table->string('image_path'); // مسیر یا URL عکس
            $table->boolean('is_main')->default(false); // آیا عکس اصلی است؟

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('ad_images');
    }
};
