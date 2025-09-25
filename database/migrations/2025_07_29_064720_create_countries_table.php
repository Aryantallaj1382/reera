<?php

// database/migrations/xxxx_xx_xx_create_countries_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');          // نام کشور (مثلا ایران)
            $table->string('code', 3)->nullable();  // کد کشور (مثلا IR)
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('countries');
    }
};
