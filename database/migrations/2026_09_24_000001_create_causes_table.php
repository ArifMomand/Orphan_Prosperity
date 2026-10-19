<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('causes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category');
            $table->string('location')->nullable();
            $table->unsignedInteger('goal')->default(0);
            $table->unsignedInteger('raised')->default(0);
            $table->unsignedInteger('donors')->default(0);
            $table->string('image_path')->nullable();
            $table->text('summary')->nullable();
            $table->text('story')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('causes');
    }
};
