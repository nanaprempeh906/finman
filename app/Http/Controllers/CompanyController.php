<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CompanyController extends Controller
{
    /**
     * Show the form for creating a new company (setup)
     */
    public function create()
    {
        return view('company.setup');
    }

    /**
     * Store a newly created company
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'about' => 'nullable|string|max:1000',
            'primary_currency' => 'required|string|in:USD,GHS,EUR,GBP,NGN,ZAR,KES',
            'opening_balance' => 'nullable|numeric|min:0|max:999999999.99',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        // Create the company
        $company = Company::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'email' => $request->email,
            'phone' => $request->phone,
            'website' => $request->website,
            'about' => $request->about,
            'logo' => $logoPath,
            'trial_ends_at' => Carbon::now()->addDays(30), // 30-day trial
            'subscription_status' => 'trial',
        ]);

        // Create the default main account
        $openingBalance = $request->opening_balance ?? 0;
        Account::create([
            'company_id' => $company->id,
            'name' => 'Main Account',
            'currency' => $request->primary_currency,
            'opening_balance' => $openingBalance,
            'current_balance' => $openingBalance,
            'is_active' => true,
            'description' => 'Default main account created during company setup',
        ]);

        // Update the current user to belong to this company and make them admin
        $user = Auth::user();
        $user->update([
            'company_id' => $company->id,
            'role' => 'admin',
        ]);

        return redirect()->route('dashboard')->with('success', 'Company setup completed successfully! Your main account has been created. Welcome to your 30-day trial.');
    }

    /**
     * Display the company profile
     */
    public function profile()
    {
        $company = Auth::user()->company;
        return view('company.profile', compact('company'));
    }

    /**
     * Show the form for editing the company
     */
    public function edit()
    {
        $company = Auth::user()->company;

        // Only admins can edit company details
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Only administrators can edit company details.');
        }

        return view('company.edit', compact('company'));
    }

    /**
     * Update the company
     */
    public function update(Request $request)
    {
        $company = Auth::user()->company;

        // Only admins can update company details
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Only administrators can edit company details.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'about' => 'nullable|string|max:1000',
            'primary_color' => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'secondary_color' => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'website' => $request->website,
            'about' => $request->about,
            'primary_color' => $request->primary_color,
            'secondary_color' => $request->secondary_color,
        ];

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if it exists
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }

            $updateData['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $company->update($updateData);

        return redirect()->route('company.profile')->with('success', 'Company profile updated successfully!');
    }

    /**
     * Get company statistics for API/AJAX calls
     */
    public function statistics()
    {
        $company = Auth::user()->company;

        return response()->json([
            'total_users' => $company->users()->count(),
            'total_transactions' => $company->transactions()->count(),
            'total_income' => $company->getTotalIncome(),
            'total_expenses' => $company->getTotalExpenses(),
            'current_balance' => $company->getCurrentBalance(),
            'subscription_status' => $company->subscription_status,
            'trial_ends_at' => $company->trial_ends_at,
            'is_on_trial' => $company->isOnTrial(),
        ]);
    }

    /**
     * Show the opening balance form
     */
    public function openingBalance()
    {
        $company = Auth::user()->company;

        return view('company.opening-balance', compact('company'));
    }

    /**
     * Store the opening balance
     */
    public function storeOpeningBalance(Request $request)
    {
        $request->validate([
            'opening_balance' => 'required|numeric|min:0|max:999999999.99',
            'opening_balance_date' => 'required|date|before_or_equal:today',
        ]);

        $company = Auth::user()->company;

        $company->setOpeningBalance(
            $request->opening_balance,
            $request->opening_balance_date
        );

        return redirect()->route('company.profile')
            ->with('success', 'Opening balance has been set successfully!');
    }
}
