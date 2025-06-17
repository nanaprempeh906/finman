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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Who created this transaction
            $table->date('transaction_date');
            $table->decimal('amount', 15, 2); // Amount of the transaction
            $table->enum('type', ['debit', 'credit']); // Transaction type
            $table->string('method')->nullable(); // cash, bank_transfer, card, etc.
            $table->decimal('fee', 15, 2)->default(0); // Transaction fee
            $table->text('description')->nullable();
            $table->string('category'); // salary, commission, expense, etc.
            $table->string('reference_number')->nullable(); // Bank reference, invoice number, etc.
            $table->decimal('balance_before', 15, 2)->nullable(); // Balance before this transaction
            $table->decimal('balance_after', 15, 2)->nullable(); // Balance after this transaction
            $table->json('metadata')->nullable(); // Additional transaction data
            $table->string('status')->default('completed'); // pending, completed, failed, cancelled
            $table->timestamps();

            // Index for better query performance
            $table->index(['company_id', 'transaction_date']);
            $table->index(['company_id', 'type']);
            $table->index(['company_id', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
