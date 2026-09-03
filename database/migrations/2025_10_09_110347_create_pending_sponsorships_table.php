<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pending_sponsorships', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->foreignId('orphan_id')->constrained()->cascadeOnDelete();
            $table->integer('duration');
            $table->decimal('bail_amount', 8, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_sponsorships');
    }
};
