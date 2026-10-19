<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cause_donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cause_id')->constrained('causes')->cascadeOnDelete();

            $table->string('donor_name')->nullable();
            $table->string('donor_email')->nullable();

            $table->decimal('amount', 10, 2);                 // USD
            $table->string('method')->default('donorbox');    // donorbox | bank_transfer | cash | other
            $table->string('reference')->nullable();          // Donorbox receipt / transfer number
            $table->text('note')->nullable();
            $table->date('donated_at');

            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['cause_id', 'donated_at']);
        });

        // Amount/donors that were typed in by hand before tracking existed
        Schema::table('causes', function (Blueprint $table) {
            $table->unsignedInteger('opening_raised')->default(0)->after('donors');
            $table->unsignedInteger('opening_donors')->default(0)->after('opening_raised');
        });

        // Keep today's numbers as the starting point
        DB::table('causes')->update([
            'opening_raised' => DB::raw('raised'),
            'opening_donors' => DB::raw('donors'),
        ]);
    }

    public function down(): void
    {
        Schema::table('causes', function (Blueprint $table) {
            $table->dropColumn(['opening_raised', 'opening_donors']);
        });

        Schema::dropIfExists('cause_donations');
    }
};