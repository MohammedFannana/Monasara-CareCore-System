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
        Schema::table('pending_sponsorships', function (Blueprint $table) {
            $table->string('success_indicator')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pending_sponsorships', function (Blueprint $table) {
            $table->dropColumn('success_indicator');
        });
    }
};
