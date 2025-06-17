<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Company;
use App\Models\Account;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create default accounts for existing companies
        Company::all()->each(function ($company) {
            // Check if company already has accounts
            if ($company->accounts()->count() === 0) {
                // Create a default account
                Account::create([
                    'company_id' => $company->id,
                    'name' => 'Main Account',
                    'currency' => 'USD', // Default currency
                    'opening_balance' => $company->opening_balance ?? 0,
                    'current_balance' => $company->opening_balance ?? 0,
                    'is_active' => true,
                    'description' => 'Default account created during multi-account upgrade',
                ]);

                // Update existing transactions to use this account
                $defaultAccount = $company->accounts()->first();
                $company->transactions()->whereNull('account_id')->update([
                    'account_id' => $defaultAccount->id
                ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove default accounts created by this migration
        Account::where('name', 'Main Account')
               ->where('description', 'Default account created during multi-account upgrade')
               ->delete();
    }
};
