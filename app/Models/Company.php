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
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
        'trial_ends_at' => 'datetime',
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
     * Get current balance
     */
    public function getCurrentBalance()
    {
        return $this->getTotalIncome() - $this->getTotalExpenses();
    }

    /**
     * Get profit (income - expenses)
     */
    public function getProfit()
    {
        return $this->getCurrentBalance();
    }
}
