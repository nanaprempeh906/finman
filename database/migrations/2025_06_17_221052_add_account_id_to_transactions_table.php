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
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('account_id')->nullable()->after('company_id')->constrained()->onDelete('cascade');

            // Add index for account-based queries
            $table->index(['account_id', 'transaction_date']);
            $table->index(['account_id', 'type']);
            $table->index(['account_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
            $table->dropIndex(['account_id', 'transaction_date']);
            $table->dropIndex(['account_id', 'type']);
            $table->dropIndex(['account_id', 'status']);
            $table->dropColumn('account_id');
        });
    }
};
