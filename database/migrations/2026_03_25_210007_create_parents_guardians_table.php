<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parents_guardians', function (Blueprint $table) {
            $table->id();
            $table->string('last_name');
            $table->string('first_name');
            $table->enum('gender', ['M', 'F'])->nullable();
            $table->date('birth_date')->nullable();
            $table->string('nationality')->nullable();
            $table->string('phone');
            $table->string('phone_2')->nullable();
            $table->string('email')->nullable();
            $table->string('whatsapp')->nullable();
            $table->enum('relationship', ['père', 'mère', 'tuteur', 'grand-parent', 'oncle_tante', 'autre']);
            $table->string('profession')->nullable();
            $table->string('employer')->nullable();
            $table->string('workplace_phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->boolean('is_alive')->default(true);
            $table->boolean('is_legal_guardian')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parents_guardians');
    }
};
