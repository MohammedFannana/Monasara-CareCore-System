<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('associations', function (Blueprint $table) {
            $table->string('role')->default('association')->after('password');
            $table->foreignId('parent_association_id')->nullable()->after('role')->constrained('associations')->nullOnDelete();
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->foreignId('association_id')->nullable()->after('type')->constrained('associations')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropConstrainedForeignId('association_id');
        });

        Schema::table('associations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('parent_association_id');
            $table->dropColumn('role');
        });
    }
};
