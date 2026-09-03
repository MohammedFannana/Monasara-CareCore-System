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
        Schema::create('gifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orphan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sponsor_id')->constrained()->cascadeOnDelete();
            $table->date('gift_date');
            $table->integer('amount')->min(1);
             $table->integer('duration')->min(1);
            $table->string('currency' , 10);
            $table->string('total');
            $table->string('payment_received')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gifts');
    }
};
