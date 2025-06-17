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
        'available_balance',
        'opening_balance_date',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
        'trial_ends_at' => 'datetime',
        'opening_balance' => 'decimal:2',
        'available_balance' => 'decimal:2',
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
     * Get total income for the company
     */
    public function getTotalIncome()
    {
        return $this->transactions()
            ->where('type', 'credit')
            ->where('status', 'completed')
            ->sum('amount');
    }

    /**
     * Get total expenses for the company
     */
    public function getTotalExpenses()
    {
        return $this->transactions()
            ->where('type', 'debit')
            ->where('status', 'completed')
            ->sum('amount');
    }

    /**
     * Get total fees paid
     */
    public function getTotalFees()
    {
        return $this->transactions()
            ->where('status', 'completed')
            ->sum('fee');
    }

    /**
     * Get current balance (opening balance + income - expenses - fees)
     */
    public function getCurrentBalance()
    {
        return $this->opening_balance + $this->getTotalIncome() - $this->getTotalExpenses() - $this->getTotalFees();
    }

    /**
     * Get available balance (current balance minus pending transactions)
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

        return $this->getCurrentBalance() - $pendingDebits - $pendingFees;
    }

    /**
     * Get profit (income - expenses, excluding opening balance)
     */
    public function getProfit()
    {
        return $this->getTotalIncome() - $this->getTotalExpenses() - $this->getTotalFees();
    }

    /**
     * Update the available balance in the database
     */
    public function updateAvailableBalance()
    {
        $this->update([
            'available_balance' => $this->getAvailableBalance()
        ]);
    }

    /**
     * Set opening balance
     */
    public function setOpeningBalance($amount, $date = null)
    {
        $this->update([
            'opening_balance' => $amount,
            'opening_balance_date' => $date ?? now()->toDateString(),
            'available_balance' => $this->getAvailableBalance()
        ]);
    }
}
