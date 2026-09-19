<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE associations MODIFY role ENUM('association', 'association_staff') NOT NULL DEFAULT 'association'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE associations MODIFY role VARCHAR(255) NOT NULL DEFAULT 'association'");
    }
};
