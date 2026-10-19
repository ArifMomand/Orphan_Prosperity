<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pioneer_applications', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('city');
            $table->string('district')->nullable();
            $table->string('experience');           // none | some | experienced
            $table->string('heard_from')->nullable();
            $table->json('availability')->nullable(); // e.g. ["weekdays","evenings"]
            $table->json('skills')->nullable();        // e.g. ["logistics","teaching"]
            $table->text('motivation');
            $table->boolean('agree_background_check')->default(false);
            $table->string('status')->default('pending'); // pending | verified | rejected
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pioneer_applications');
    }
};
