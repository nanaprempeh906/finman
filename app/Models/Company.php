<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'primary_color',
        'secondary_color',
        'about',
        'email',
        'phone',
        'website',
        'settings',
        'is_active',
        'trial_ends_at',
        'subscription_status',
        'opening_balance',
        'opening_balance_date',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
        'trial_ends_at' => 'datetime',
        'opening_balance' => 'decimal:2',
        'opening_balance_date' => 'date',
    ];

    /**
     * Boot method to automatically generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($company) {
            if (empty($company->slug)) {
                $company->slug = Str::slug($company->name);
            }
        });
    }

    /**
     * Relationship with users
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relationship with accounts
     */
    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    /**
     * Relationship with transactions
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the admin user(s) for this company
     */
    public function admins()
    {
        return $this->users()->where('role', 'admin');
    }

    /**
     * Check if company is on trial
     */
    public function isOnTrial()
    {
        return $this->subscription_status === 'trial' &&
               $this->trial_ends_at &&
               $this->trial_ends_at->isFuture();
    }

    /**
     * Check if company has active subscription
     */
    public function hasActiveSubscription()
    {
        return in_array($this->subscription_status, ['active', 'trial']) && $this->is_active;
    }

    /**
     * Get total income across all accounts
     */
    public function getTotalIncome()
    {
        return $this->accounts()->get()->sum(function($account) {
            return $account->getTotalIncome();
        });
    }

    /**
     * Get total expenses across all accounts
     */
    public function getTotalExpenses()
    {
        return $this->accounts()->get()->sum(function($account) {
            return $account->getTotalExpenses();
        });
    }

    /**
     * Get total fees across all accounts
     */
    public function getTotalFees()
    {
        return $this->accounts()->get()->sum(function($account) {
            return $account->getTotalFees();
        });
    }

    /**
     * Get total current balance across all accounts (converted to base currency)
     */
    public function getTotalCurrentBalance()
    {
        return $this->accounts()->get()->sum(function($account) {
            return $account->calculateCurrentBalance();
        });
    }

    /**
     * Get total available balance across all accounts
     */
    public function getTotalAvailableBalance()
    {
        return $this->accounts()->get()->sum(function($account) {
            return $account->getAvailableBalance();
        });
    }

    /**
     * Get profit across all accounts
     */
    public function getProfit()
    {
        return $this->getTotalIncome() - $this->getTotalExpenses() - $this->getTotalFees();
    }

    /**
     * Get accounts grouped by currency
     */
    public function getAccountsByCurrency()
    {
        return $this->accounts()->get()->groupBy('currency');
    }

    /**
     * Get primary currency (most used currency)
     */
    public function getPrimaryCurrency()
    {
        $currencies = $this->accounts()->get()->countBy('currency');
        return $currencies->keys()->first() ?? 'USD';
    }

    /**
     * Format amount in primary currency
     */
    public function formatAmount($amount)
    {
        $currency = $this->getPrimaryCurrency();
        $symbols = [
            'USD' => '$',
            'GHS' => '₵',
            'EUR' => '€',
            'GBP' => '£',
            'NGN' => '₦',
            'ZAR' => 'R',
            'KES' => 'KSh',
        ];

        $symbol = $symbols[$currency] ?? $currency . ' ';
        return $symbol . number_format($amount, 2);
    }

    /**
     * Placeholder for backward compatibility - no longer updates database
     */
    public function updateAvailableBalance()
    {
        // No longer needed - balances are maintained at account level
        return true;
    }
}
