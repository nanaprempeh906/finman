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
        Schema::table('companies', function (Blueprint $table) {
            $table->decimal('opening_balance', 15, 2)->default(0.00)->after('subscription_status');
            $table->decimal('available_balance', 15, 2)->default(0.00)->after('opening_balance');
            $table->date('opening_balance_date')->nullable()->after('available_balance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['opening_balance', 'available_balance', 'opening_balance_date']);
        });
    }
};
