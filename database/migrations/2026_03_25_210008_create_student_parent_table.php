<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_parent', function (Blueprint $table) {
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->constrained('parents_guardians')->cascadeOnDelete();
            $table->boolean('is_primary_contact')->default(false);
            $table->boolean('is_financial_responsible')->default(false);
            $table->boolean('is_pickup_authorized')->default(true);
            $table->timestamps();

            $table->primary(['student_id', 'parent_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_parent');
    }
};
