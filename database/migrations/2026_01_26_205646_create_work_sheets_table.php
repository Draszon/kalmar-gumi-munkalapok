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
        Schema::create('work_sheets', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number');
            $table->string('name')->nullable();
            $table->string('car_type')->nullable();
            $table->json('used_materials')->nullable();
            $table->json('services')->nullable();
            $table->string('tire_brand')->nullable();
            $table->string('tire_size')->nullable();
            $table->boolean('store')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_sheets');
    }
};
