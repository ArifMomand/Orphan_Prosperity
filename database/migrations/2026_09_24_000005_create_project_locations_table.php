<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');            // e.g. "Kabul"
            $table->text('description')->nullable();
            $table->decimal('x_percent', 5, 2); // 0-100, horizontal position on the map
            $table->decimal('y_percent', 5, 2); // 0-100, vertical position on the map
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_locations');
    }
};
