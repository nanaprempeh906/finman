<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'currency',
        'opening_balance',
        'current_balance',
        'is_active',
        'description',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Boot method to set up model observers
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($account) {
            // Set current balance to opening balance when creating
            $account->current_balance = $account->opening_balance;
        });

        static::deleting(function ($account) {
            // Prevent deletion if transactions exist
            if ($account->transactions()->count() > 0) {
                throw new \Exception('Cannot delete account with existing transactions. Archive the account instead.');
            }
        });
    }

    /**
     * Relationship with company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Relationship with transactions
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Scope to filter accounts by company
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Scope to get active accounts only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get accounts by currency
     */
    public function scopeByCurrency($query, $currency)
    {
        return $query->where('currency', $currency);
    }

    /**
     * Get total income for this account
     */
    public function getTotalIncome()
    {
        return $this->transactions()
            ->where('type', 'credit')
            ->where('status', 'completed')
            ->sum('amount');
    }

    /**
     * Get total expenses for this account
     */
    public function getTotalExpenses()
    {
        return $this->transactions()
            ->where('type', 'debit')
            ->where('status', 'completed')
            ->sum('amount');
    }

    /**
     * Get total fees for this account
     */
    public function getTotalFees()
    {
        return $this->transactions()
            ->where('status', 'completed')
            ->sum('fee');
    }

    /**
     * Calculate current balance (opening balance + income - expenses - fees)
     */
    public function calculateCurrentBalance()
    {
        return $this->opening_balance + $this->getTotalIncome() - $this->getTotalExpenses() - $this->getTotalFees();
    }

    /**
     * Update current balance in the database
     */
    public function updateCurrentBalance()
    {
        $this->update([
            'current_balance' => $this->calculateCurrentBalance()
        ]);
    }

    /**
     * Get available balance (current balance minus pending debits)
     */
    public function getAvailableBalance()
    {
        $pendingDebits = $this->transactions()
            ->where('type', 'debit')
            ->where('status', 'pending')
            ->sum('amount');

        $pendingFees = $this->transactions()
            ->where('status', 'pending')
            ->sum('fee');

        return $this->calculateCurrentBalance() - $pendingDebits - $pendingFees;
    }

    /**
     * Get formatted balance with currency
     */
    public function getFormattedBalanceAttribute()
    {
        return $this->formatCurrency($this->calculateCurrentBalance());
    }

    /**
     * Get formatted available balance with currency
     */
    public function getFormattedAvailableBalanceAttribute()
    {
        return $this->formatCurrency($this->getAvailableBalance());
    }

    /**
     * Format currency based on account currency
     */
    public function formatCurrency($amount)
    {
        $currencySymbols = [
            'USD' => '$',
            'GHS' => '₵',
            'EUR' => '€',
            'GBP' => '£',
            'NGN' => '₦',
            'ZAR' => 'R',
            'KES' => 'KSh',
        ];

        $symbol = $currencySymbols[$this->currency] ?? $this->currency . ' ';

        return $symbol . number_format($amount, 2);
    }

    /**
     * Get currency symbol
     */
    public function getCurrencySymbolAttribute()
    {
        $currencySymbols = [
            'USD' => '$',
            'GHS' => '₵',
            'EUR' => '€',
            'GBP' => '£',
            'NGN' => '₦',
            'ZAR' => 'R',
            'KES' => 'KSh',
        ];

        return $currencySymbols[$this->currency] ?? $this->currency;
    }

    /**
     * Archive account instead of deleting
     */
    public function archive()
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Restore archived account
     */
    public function restore()
    {
        $this->update(['is_active' => true]);
    }
}
