<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('matricule')->unique();
            $table->string('last_name');
            $table->string('first_name');
            $table->date('birth_date');
            $table->string('birth_place');
            $table->string('birth_country')->default("Côte d'Ivoire");
            $table->string('birth_certificate_number')->nullable();
            $table->date('birth_certificate_date')->nullable();
            $table->string('birth_certificate_place')->nullable();
            $table->enum('gender', ['M', 'F']);
            $table->string('nationality');
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('country')->default("Côte d'Ivoire");
            $table->string('blood_type')->nullable();
            $table->boolean('has_disability')->default(false);
            $table->text('disability_description')->nullable();
            $table->text('chronic_illness')->nullable();
            $table->text('allergies')->nullable();
            $table->text('medical_notes')->nullable();
            $table->enum('family_situation', ['unis', 'divorcés', 'père_décédé', 'mère_décédée', 'orphelin', 'tuteur'])->nullable();
            $table->integer('siblings_count')->nullable();
            $table->integer('rank_in_siblings')->nullable();
            $table->string('previous_school')->nullable();
            $table->string('previous_school_city')->nullable();
            $table->string('previous_class')->nullable();
            $table->string('previous_year')->nullable();
            $table->enum('admission_type', ['nouveau', 'redoublant', 'transféré'])->default('nouveau');
            $table->boolean('has_transfer_certificate')->default(false);
            $table->string('photo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
