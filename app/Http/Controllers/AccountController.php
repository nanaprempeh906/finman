<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    /**
     * Display a listing of the accounts.
     */
    public function index()
    {
        $company = Auth::user()->company;
        $accounts = $company->accounts()
            ->withCount('transactions')
            ->orderBy('is_active', 'desc')
            ->orderBy('name')
            ->get();

        // Calculate current balances for each account
        foreach ($accounts as $account) {
            $account->current_balance = $account->calculateCurrentBalance();
        }

        return view('accounts.index', compact('accounts', 'company'));
    }

    /**
     * Show the form for creating a new account.
     */
    public function create()
    {
        $company = Auth::user()->company;

        $currencies = [
            'USD' => 'US Dollar ($)',
            'GHS' => 'Ghana Cedi (₵)',
            'EUR' => 'Euro (€)',
            'GBP' => 'British Pound (£)',
            'NGN' => 'Nigerian Naira (₦)',
            'ZAR' => 'South African Rand (R)',
            'KES' => 'Kenyan Shilling (KSh)',
        ];

        return view('accounts.create', compact('company', 'currencies'));
    }

    /**
     * Store a newly created account in storage.
     */
    public function store(Request $request)
    {
        $company = Auth::user()->company;

        $request->validate([
            'name' => 'required|string|max:255',
            'currency' => 'required|string|size:3',
            'opening_balance' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
        ]);

        $account = $company->accounts()->create([
            'name' => $request->name,
            'currency' => strtoupper($request->currency),
            'opening_balance' => $request->opening_balance,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return redirect()->route('accounts.index')
            ->with('success', 'Account created successfully.');
    }

    /**
     * Display the specified account.
     */
    public function show(Account $account)
    {
        // Ensure account belongs to user's company
        if ($account->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        $account->load(['transactions' => function($query) {
            $query->orderBy('transaction_date', 'desc')->take(10);
        }]);

        // Calculate balances
        $account->current_balance = $account->calculateCurrentBalance();
        $account->available_balance = $account->getAvailableBalance();

        $stats = [
            'total_income' => $account->getTotalIncome(),
            'total_expenses' => $account->getTotalExpenses(),
            'total_fees' => $account->getTotalFees(),
            'transaction_count' => $account->transactions()->count(),
            'pending_transactions' => $account->transactions()->where('status', 'pending')->count(),
        ];

        return view('accounts.show', compact('account', 'stats'));
    }

    /**
     * Show the form for editing the specified account.
     */
    public function edit(Account $account)
    {
        // Ensure account belongs to user's company
        if ($account->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        $currencies = [
            'USD' => 'US Dollar ($)',
            'GHS' => 'Ghana Cedi (₵)',
            'EUR' => 'Euro (€)',
            'GBP' => 'British Pound (£)',
            'NGN' => 'Nigerian Naira (₦)',
            'ZAR' => 'South African Rand (R)',
            'KES' => 'Kenyan Shilling (KSh)',
        ];

        return view('accounts.edit', compact('account', 'currencies'));
    }

    /**
     * Update the specified account in storage.
     */
    public function update(Request $request, Account $account)
    {
        // Ensure account belongs to user's company
        if ($account->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'currency' => 'required|string|size:3',
            'opening_balance' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $account->update([
            'name' => $request->name,
            'currency' => strtoupper($request->currency),
            'opening_balance' => $request->opening_balance,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        // Recalculate current balance after opening balance change
        $account->updateCurrentBalance();

        return redirect()->route('accounts.index')
            ->with('success', 'Account updated successfully.');
    }

    /**
     * Remove the specified account from storage.
     */
    public function destroy(Account $account)
    {
        // Ensure account belongs to user's company
        if ($account->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        try {
            $account->delete();
            return redirect()->route('accounts.index')
                ->with('success', 'Account deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('accounts.index')
                ->with('error', 'Cannot delete account with existing transactions. Archive the account instead.');
        }
    }

    /**
     * Archive the specified account.
     */
    public function archive(Account $account)
    {
        // Ensure account belongs to user's company
        if ($account->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        $account->archive();

        return redirect()->route('accounts.index')
            ->with('success', 'Account archived successfully.');
    }

    /**
     * Restore the specified account.
     */
    public function restore(Account $account)
    {
        // Ensure account belongs to user's company
        if ($account->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        $account->restore();

        return redirect()->route('accounts.index')
            ->with('success', 'Account restored successfully.');
    }
}
