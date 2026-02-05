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
        Schema::table('work_sheets', function (Blueprint $table) {
            $table->integer('store_qty')->default(1)->after('store');
            $table->boolean('store_tire')->default(false)->after('store_qty');
            $table->boolean('store_wheel')->default(false)->after('store_tire');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_sheets', function (Blueprint $table) {
            $table->dropColumn(['store_qty', 'store_tire', 'store_wheel']);
        });
    }
};
