<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('needy_people', function (Blueprint $table) {
            $table->unsignedTinyInteger('age')->nullable()->after('category');
            $table->unsignedTinyInteger('family_size')->nullable()->after('age');
            $table->text('story')->nullable()->after('family_size');
        });

        Schema::table('aid_packages', function (Blueprint $table) {
            $table->text('description')->nullable()->after('currency');
            $table->text('includes')->nullable()->after('description'); // one item per line
        });
    }

    public function down(): void
    {
        Schema::table('needy_people', function (Blueprint $table) {
            $table->dropColumn(['age', 'family_size', 'story']);
        });

        Schema::table('aid_packages', function (Blueprint $table) {
            $table->dropColumn(['description', 'includes']);
        });
    }
};
