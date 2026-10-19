<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aman_ids', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();                 // used in public URLs (not guessable)
            $table->string('aman_id')->nullable()->unique(); // e.g. A-0000001
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('first_name');
            $table->string('last_name');
            $table->string('country');
            $table->string('province');
            $table->string('district');
            $table->string('gender');
            $table->string('primary_phone');
            $table->string('secondary_phone')->nullable();

            $table->string('registered_for')->default('other'); // self | family | other
            $table->json('categories')->nullable();             // orphan, widow, ...
            $table->json('needs')->nullable();                  // selected package slugs

            $table->boolean('cash_need')->default(false);
            $table->decimal('cash_amount', 10, 2)->nullable();

            $table->enum('visibility', ['private', 'public'])->default('private');
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aman_ids');
    }
};