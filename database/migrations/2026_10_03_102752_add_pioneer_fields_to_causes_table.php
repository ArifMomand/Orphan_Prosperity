<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('causes', function (Blueprint $table) {
            $table->foreignId('pioneer_id')->nullable()->constrained('pioneers')->nullOnDelete();
            $table->string('orphan_name')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('causes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('pioneer_id');
            $table->dropColumn('orphan_name');
        });
    }
};