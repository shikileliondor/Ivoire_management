<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->cascadeOnDelete();
            $table->string('receipt_number')->unique();
            $table->decimal('amount', 10, 0);
            $table->date('payment_date');
            $table->enum('payment_type', ['inscription', 'tranche_1', 'tranche_2', 'tranche_3']);
            $table->enum('payment_method', ['espèces', 'mobile_money', 'chèque', 'virement']);
            $table->foreignId('received_by')->constrained('users')->cascadeOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
