<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aid_packages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category', 50)->index();   // Food, Shelter, Cleaning Supplies, ...
            $table->decimal('price', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aid_packages');
    }
};
