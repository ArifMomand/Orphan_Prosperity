<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('needy_people', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category', 50)->index();   // Women, Children, Elderly, ...
            $table->string('city')->nullable()->index();
            $table->string('country')->default('Afghanistan');
            $table->string('country_code', 2)->default('AF');
            $table->string('image_path')->nullable();
            $table->boolean('is_verified')->default(true);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('needy_people');
    }
};
