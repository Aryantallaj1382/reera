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
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // مثلاً "فارسی" یا "English"
            $table->timestamps();
        });
        Schema::create('nationalities', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // مثلاً "ایرانی" یا "آلمانی"
            $table->timestamps();
        });
        Schema::table('users', function (Blueprint $table) {
            // حذف فیلد name
            $table->dropColumn('name');

            // افزودن فیلدها
            $table->string('first_name')->after('id');
            $table->string('last_name')->after('first_name');
            $table->string('mobile')->unique()->after('email');
            $table->timestamp('mobile_verified_at')->nullable()->after('mobile');
            $table->string('national_code')->nullable()->after('mobile_verified_at');

            $table->unsignedBigInteger('language_id')->nullable()->after('national_code');
            $table->unsignedBigInteger('nationality_id')->nullable()->after('language_id');

            $table->string('profile')->nullable()->after('nationality_id');
            $table->string('identity_document')->nullable()->after('profile');

            // کلید خارجی
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('set null');
            $table->foreign('nationality_id')->references('id')->on('nationalities')->onDelete('set null');
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
