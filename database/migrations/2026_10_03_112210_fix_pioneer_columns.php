<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pioneers', function (Blueprint $table) {
            if (! Schema::hasColumn('pioneers', 'name')) {
                $table->string('name')->after('id');
            }
            if (! Schema::hasColumn('pioneers', 'email')) {
                $table->string('email')->unique()->after('name');
            }
            if (! Schema::hasColumn('pioneers', 'password')) {
                $table->string('password')->after('email');
            }
            if (! Schema::hasColumn('pioneers', 'remember_token')) {
                $table->rememberToken()->after('password');
            }
        });

        Schema::table('causes', function (Blueprint $table) {
            if (! Schema::hasColumn('causes', 'pioneer_id')) {
                $table->foreignId('pioneer_id')->nullable()->constrained('pioneers')->nullOnDelete();
            }
            if (! Schema::hasColumn('causes', 'orphan_name')) {
                $table->string('orphan_name')->nullable();
            }
        });
    }

    public function down(): void
    {
        // Intentionally left empty: these columns belong to the earlier migrations.
    }
};