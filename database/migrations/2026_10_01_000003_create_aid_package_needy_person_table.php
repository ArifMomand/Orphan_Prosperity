<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // The "Wishlist" shown under each person's name.
        Schema::create('aid_package_needy_person', function (Blueprint $table) {
            $table->id();
            $table->foreignId('needy_person_id')->constrained('needy_people')->cascadeOnDelete();
            $table->foreignId('aid_package_id')->constrained('aid_packages')->cascadeOnDelete();
            $table->unique(['needy_person_id', 'aid_package_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aid_package_needy_person');
    }
};
