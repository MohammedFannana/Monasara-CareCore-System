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
        Schema::create('orphans', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('name');
            $table->enum('role' , ['candidate','auditor','rejected','certified','waiting','sponsored'])->default('candidate');
            $table->foreignId('association_id')->constrained()->cascadeOnDelete();
            $table->date('birth_date');
            $table->string('birth_place');
            $table->string('country');
            $table->string('city');
            $table->string('landmark');
            $table->string('id_number')->unique();
            $table->string('password');
            $table->enum('orphan_status' , ['يتيم الأم' , 'يتيم الأب' , 'يتيم الأبوين'])->default('يتيم الأب');
            $table->enum('gender' , ['ذكر' , 'أنثى']);
            $table->string('nominating_authority')->nullable();

            $table->string('mother_name');
            $table->date('death_mother_date')->nullable();
            $table->string('cause_mother_death')->nullable();

            $table->string('father_name');
            $table->date('death_father_date')->nullable();
            $table->string('cause_father_death')->nullable();

            $table->string('mother_id_number')->nullable();
            $table->enum('mother_marital_status' , ['أرملة' , 'متزوجة'])->nullable();
            $table->string('mother_phone')->nullable();

            $table->string('father_id_number')->nullable();
            $table->enum('father_marital_status' , ['أرمل' , 'متزوج'])->nullable();
            $table->string('father_phone')->nullable();

            $table->enum('income' , ['بدون دخل' , 'دخل ثايت']);
            $table->string('income_value')->nullable();
            $table->string('income_source')->nullable();
            $table->string('father_death_certificate')->nullable();
            $table->string('not_available_father_death')->nullable();
            $table->string('mother_death_certificate')->nullable();
            $table->string('not_available_mother_death')->nullable();

            $table->string('guardian_name');
            $table->string('guardian_relation');
            $table->string('guardian_jop');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orphans');
    }
};
