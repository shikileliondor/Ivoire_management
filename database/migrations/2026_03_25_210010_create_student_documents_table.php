<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->enum('document_type', ['acte_naissance', 'carnet_vaccination', 'certificat_transfert', 'photo_identite', 'certificat_medical', 'jugement_tutelle', 'autre']);
            $table->string('label');
            $table->string('file_path')->nullable();
            $table->boolean('is_original')->default(false);
            $table->date('received_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_documents');
    }
};
