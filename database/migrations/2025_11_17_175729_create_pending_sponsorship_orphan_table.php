<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('pending_sponsorship_orphan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pending_sponsorship_id')->constrained('pending_sponsorships')->onDelete('cascade');
            $table->foreignId('orphan_id')->constrained('orphans')->onDelete('cascade');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_sponsorship_orphan');
    }
};
