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
            $table->boolean('storage')->nullable()->default(false)->after('system');
            $table->boolean('cooling')->nullable()->default(false)->after('system');
            $table->boolean('heating')->nullable()->default(false)->after('system');
            $table->boolean('open_kitchen')->nullable()->default(false)->after('system');
            $table->boolean('cabinets')->nullable()->default(false)->after('system');
            $table->boolean('flooring')->nullable()->default(false)->after('system');
            $table->boolean('security_door')->nullable()->default(false)->after('system');
            $table->boolean('double_glazed_windows')->nullable()->default(false)->after('system');
            $table->boolean('system')->nullable()->default(false)->after('system');
            $table->boolean('security_guard')->nullable()->default(false)->after('system');
            $table->boolean('cctv')->nullable()->default(false)->after('system');
            $table->boolean('generator')->nullable()->default(false)->after('system');
            $table->boolean('master_bedroom')->nullable()->default(false)->after('system');
            $table->boolean('guest_hall')->nullable()->default(false)->after('system');
            $table->boolean('gym')->nullable()->default(false)->after('system');
            $table->boolean('pool')->nullable()->default(false)->after('system');
            $table->boolean('internet')->nullable()->default(false)->after('system');
            $table->boolean('empty')->nullable()->default(false)->after('system');
            $table->boolean('in_use')->nullable()->default(false)->after('system');
            $table->boolean('text')->nullable()->default(false)->after('system');

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
