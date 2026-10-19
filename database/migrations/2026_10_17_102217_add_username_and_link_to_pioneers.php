<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pioneers', function (Blueprint $table) {
            if (! Schema::hasColumn('pioneers', 'username')) {
                $table->string('username')->nullable()->unique()->after('name');
            }
        });

        Schema::table('pioneer_applications', function (Blueprint $table) {
            if (! Schema::hasColumn('pioneer_applications', 'pioneer_id')) {
                $table->foreignId('pioneer_id')->nullable()->constrained('pioneers')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        //
    }
};