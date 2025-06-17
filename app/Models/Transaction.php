<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'account_id',
        'user_id',
        'transaction_date',
        'amount',
        'type',
        'method',
        'fee',
        'description',
        'category',
        'reference_number',
        'balance_before',
        'balance_after',
        'metadata',
        'status',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'metadata' => 'array',
    ];

    /**
     * Boot method to set up model observers
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if ($transaction->account_id) {
                $account = $transaction->account;
                $transaction->balance_before = $account->calculateCurrentBalance();

                // Calculate balance after transaction
                if ($transaction->type === 'credit') {
                    $transaction->balance_after = $transaction->balance_before + $transaction->amount - $transaction->fee;
                } else {
                    $transaction->balance_after = $transaction->balance_before - $transaction->amount - $transaction->fee;
                }
            }
        });

        static::created(function ($transaction) {
            // Update account's current balance after transaction is created
            if ($transaction->account_id) {
                $transaction->account->updateCurrentBalance();
            }
            // Also update company balance for backward compatibility
            $transaction->company->updateAvailableBalance();
        });

        static::updated(function ($transaction) {
            // Update account's current balance after transaction is updated
            if ($transaction->account_id) {
                $transaction->account->updateCurrentBalance();
            }
            // Also update company balance for backward compatibility
            $transaction->company->updateAvailableBalance();
        });

        static::deleted(function ($transaction) {
            // Update account's current balance after transaction is deleted
            if ($transaction->account_id) {
                $transaction->account->updateCurrentBalance();
            }
            // Also update company balance for backward compatibility
            $transaction->company->updateAvailableBalance();
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
     * Relationship with account
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Relationship with user who created the transaction
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to filter transactions by company
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Scope to filter transactions by account
     */
    public function scopeForAccount($query, $accountId)
    {
        return $query->where('account_id', $accountId);
    }

    /**
     * Scope to get income transactions (credits)
     */
    public function scopeIncome($query)
    {
        return $query->where('type', 'credit');
    }

    /**
     * Scope to get expense transactions (debits)
     */
    public function scopeExpenses($query)
    {
        return $query->where('type', 'debit');
    }

    /**
     * Scope to get transactions by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }

    /**
     * Scope to get transactions by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to get completed transactions
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Get transactions for current month
     */
    public function scopeCurrentMonth($query)
    {
        return $query->whereBetween('transaction_date', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ]);
    }

    /**
     * Get transactions for current year
     */
    public function scopeCurrentYear($query)
    {
        return $query->whereBetween('transaction_date', [
            Carbon::now()->startOfYear(),
            Carbon::now()->endOfYear()
        ]);
    }

    /**
     * Check if transaction is income
     */
    public function isIncome()
    {
        return $this->type === 'credit';
    }

    /**
     * Check if transaction is expense
     */
    public function isExpense()
    {
        return $this->type === 'debit';
    }

    /**
     * Get formatted amount with currency
     */
    public function getFormattedAmountAttribute()
    {
        if ($this->account) {
            return $this->account->formatCurrency($this->amount);
        }
        return '$' . number_format($this->amount, 2);
    }

    /**
     * Get net amount (amount - fee)
     */
    public function getNetAmountAttribute()
    {
        return $this->amount - $this->fee;
    }
}
