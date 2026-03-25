<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_academic_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('academic_year');
            $table->string('school_name');
            $table->string('class_name');
            $table->enum('result', ['admis', 'redoublant', 'transféré', 'exclu', 'abandonné']);
            $table->decimal('average', 4, 2)->nullable();
            $table->integer('rank')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_academic_history');
    }
};
