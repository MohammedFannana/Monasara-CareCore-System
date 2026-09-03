<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $tables = ['sponsors', 'orphans', 'associations', 'researchers'];

        foreach ($tables as $tableName) {
            if (!Schema::hasColumn($tableName, 'remember_token')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->rememberToken()->nullable()->after('password');
                });
            }
        }
    }

    public function down(): void
    {
        $tables = ['sponsors', 'orphans', 'associations', 'researchers'];

        foreach ($tables as $tableName) {
            if (Schema::hasColumn($tableName, 'remember_token')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('remember_token');
                });
            }
        }
    }
};
