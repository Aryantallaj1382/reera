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
        Schema::create('user_languages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('language_id')->constrained()->onDelete('cascade');

            $table->enum('level', ['basic', 'intermediate', 'advanced', 'native']);

            $table->timestamps();
        });

        Schema::create('work_experiences', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('title'); // عنوان شغل
            $table->string('company_name'); // نام شرکت

            $table->unsignedTinyInteger('start_month'); // ماه شروع (1 تا 12)
            $table->unsignedSmallInteger('start_year'); // سال شروع

            $table->unsignedTinyInteger('end_month')->nullable(); // ماه پایان
            $table->unsignedSmallInteger('end_year')->nullable(); // سال پایان

            $table->boolean('is_current')->default(false); // آیا هنوز مشغوله

            $table->text('description')->nullable(); // توضیحات

            $table->timestamps();
        });


        Schema::create('educations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('major'); // رشته تحصیلی
            $table->string('university_name'); // نام دانشگاه

            $table->enum('degree', ['diploma', 'associate', 'bachelor', 'master', 'phd'])->nullable(); // مقطع

            $table->unsignedSmallInteger('start_year'); // سال شروع
            $table->unsignedSmallInteger('end_year')->nullable(); // سال پایان

            $table->boolean('is_current')->default(false); // آیا هنوز مشغوله

            $table->text('description')->nullable(); // توضیحات

            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_languages');
    }
};
