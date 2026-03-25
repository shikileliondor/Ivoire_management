<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('absenceable_id');
            $table->string('absenceable_type');
            $table->date('absent_date');
            $table->string('reason')->nullable();
            $table->boolean('is_justified')->default(false);
            $table->foreignId('justified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['absenceable_id', 'absenceable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absences');
    }
};
