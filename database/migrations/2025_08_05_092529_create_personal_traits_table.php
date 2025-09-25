<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

        public function up()
    {
        Schema::create('personal_traits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('housemate_id')->constrained('housemates')->onDelete('cascade');
            $table->string('trait'); // ویژگی (مثلاً اجتماعی، منظم، ورزشکار و ...)
            $table->integer('number')->nullable(); // فیلد شماره
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_traits');
    }
};
