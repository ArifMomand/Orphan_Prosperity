<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aman_donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beneficiary_id')->constrained('aman_ids')->cascadeOnDelete();

            $table->string('donor_name')->nullable();
            $table->string('donor_email')->nullable();

            $table->decimal('amount', 10, 2);                 // USD
            $table->string('need')->default('general');       // general | cash | a need slug
            $table->string('method')->default('donorbox');    // donorbox | bank_transfer | cash | other
            $table->string('reference')->nullable();          // Donorbox receipt / transfer number
            $table->text('note')->nullable();
            $table->date('donated_at');

            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['beneficiary_id', 'donated_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aman_donations');
    }
};