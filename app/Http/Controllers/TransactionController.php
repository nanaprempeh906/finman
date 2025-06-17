<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions
     */
    public function index(Request $request)
    {
        $company = Auth::user()->company;

        $query = Transaction::forCompany($company->id)->with('user');

        // Filter by type if requested
        if ($request->has('type') && in_array($request->type, ['credit', 'debit'])) {
            $query->where('type', $request->type);
        }

        // Filter by category if requested
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Filter by date range if requested
        if ($request->has('date_from') && $request->date_from) {
            $query->where('transaction_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->where('transaction_date', '<=', $request->date_to);
        }

        // Search by description
        if ($request->has('search') && $request->search) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $transactions = $query->orderBy('transaction_date', 'desc')
                             ->orderBy('created_at', 'desc')
                             ->paginate(20);

        // Get summary statistics
        $totalIncome = Transaction::forCompany($company->id)->income()->completed()->sum('amount');
        $totalExpenses = Transaction::forCompany($company->id)->expenses()->completed()->sum('amount');
        $totalFees = Transaction::forCompany($company->id)->completed()->sum('fee');
        $openingBalance = $company->opening_balance;
        $currentBalance = $company->getCurrentBalance();

        // Get categories for filter dropdown
        $categories = Transaction::forCompany($company->id)
                                ->select('category')
                                ->distinct()
                                ->whereNotNull('category')
                                ->orderBy('category')
                                ->pluck('category');

        return view('transactions.index', compact(
            'transactions',
            'totalIncome',
            'totalExpenses',
            'totalFees',
            'openingBalance',
            'currentBalance',
            'categories'
        ));
    }

    /**
     * Show the form for creating a new transaction
     */
    public function create()
    {
        $categories = [
            'revenue' => 'Revenue',
            'sales' => 'Sales',
            'consulting' => 'Consulting',
            'investment' => 'Investment',
            'loan' => 'Loan',
            'office_supplies' => 'Office Supplies',
            'marketing' => 'Marketing',
            'software' => 'Software',
            'travel' => 'Travel',
            'utilities' => 'Utilities',
            'rent' => 'Rent',
            'insurance' => 'Insurance',
            'legal' => 'Legal',
            'professional_services' => 'Professional Services',
            'equipment' => 'Equipment',
            'other' => 'Other'
        ];

        return view('transactions.create', compact('categories'));
    }

    /**
     * Store a newly created transaction
     */
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01|max:999999999.99',
            'type' => 'required|in:credit,debit',
            'category' => 'required|string|max:100',
            'transaction_date' => 'required|date|before_or_equal:today',
            'method' => 'nullable|string|in:cash,bank_transfer,credit_card,debit_card,check,digital_wallet,other',
            'fee' => 'nullable|numeric|min:0|max:999999.99',
            'notes' => 'nullable|string|max:1000',
            'reference_number' => 'nullable|string|max:100',
        ]);

        $company = Auth::user()->company;

        Transaction::create([
            'company_id' => $company->id,
            'user_id' => Auth::id(),
            'description' => $request->description,
            'amount' => $request->amount,
            'type' => $request->type,
            'category' => $request->category,
            'transaction_date' => $request->transaction_date,
            'method' => $request->method,
            'fee' => $request->fee ?? 0,
            'notes' => $request->notes,
            'reference_number' => $request->reference_number,
            'status' => 'completed', // Default to completed
        ]);

        return redirect()->route('transactions.index')
                        ->with('success', 'Transaction added successfully!');
    }

    /**
     * Display the specified transaction
     */
    public function show(Transaction $transaction)
    {
        // Ensure transaction belongs to user's company
        if ($transaction->company_id !== Auth::user()->company_id) {
            abort(403, 'Unauthorized access to transaction.');
        }

        $transaction->load('user');

        return view('transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified transaction
     */
    public function edit(Transaction $transaction)
    {
        // Ensure transaction belongs to user's company
        if ($transaction->company_id !== Auth::user()->company_id) {
            abort(403, 'Unauthorized access to transaction.');
        }

        $categories = [
            'revenue' => 'Revenue',
            'sales' => 'Sales',
            'consulting' => 'Consulting',
            'investment' => 'Investment',
            'loan' => 'Loan',
            'office_supplies' => 'Office Supplies',
            'marketing' => 'Marketing',
            'software' => 'Software',
            'travel' => 'Travel',
            'utilities' => 'Utilities',
            'rent' => 'Rent',
            'insurance' => 'Insurance',
            'legal' => 'Legal',
            'professional_services' => 'Professional Services',
            'equipment' => 'Equipment',
            'other' => 'Other'
        ];

        return view('transactions.edit', compact('transaction', 'categories'));
    }

    /**
     * Update the specified transaction
     */
    public function update(Request $request, Transaction $transaction)
    {
        // Ensure transaction belongs to user's company
        if ($transaction->company_id !== Auth::user()->company_id) {
            abort(403, 'Unauthorized access to transaction.');
        }

        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01|max:999999999.99',
            'type' => 'required|in:credit,debit',
            'category' => 'required|string|max:100',
            'transaction_date' => 'required|date|before_or_equal:today',
            'method' => 'nullable|string|in:cash,bank_transfer,credit_card,debit_card,check,digital_wallet,other',
            'fee' => 'nullable|numeric|min:0|max:999999.99',
            'notes' => 'nullable|string|max:1000',
            'reference_number' => 'nullable|string|max:100',
        ]);

        $transaction->update([
            'description' => $request->description,
            'amount' => $request->amount,
            'type' => $request->type,
            'category' => $request->category,
            'transaction_date' => $request->transaction_date,
            'method' => $request->method,
            'fee' => $request->fee ?? 0,
            'notes' => $request->notes,
            'reference_number' => $request->reference_number,
        ]);

        return redirect()->route('transactions.index')
                        ->with('success', 'Transaction updated successfully!');
    }

    /**
     * Remove the specified transaction
     */
    public function destroy(Transaction $transaction)
    {
        // Ensure transaction belongs to user's company
        if ($transaction->company_id !== Auth::user()->company_id) {
            abort(403, 'Unauthorized access to transaction.');
        }

        $transaction->delete();

        return redirect()->route('transactions.index')
                        ->with('success', 'Transaction deleted successfully!');
    }

    /**
     * Export transactions to CSV
     */
    public function export(Request $request)
    {
        $company = Auth::user()->company;

        $query = Transaction::forCompany($company->id)->with('user');

        // Apply same filters as index
        if ($request->has('type') && in_array($request->type, ['credit', 'debit'])) {
            $query->where('type', $request->type);
        }

        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->where('transaction_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->where('transaction_date', '<=', $request->date_to);
        }

        if ($request->has('search') && $request->search) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->get();

        $filename = 'transactions_' . $company->slug . '_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'Date',
                'Description',
                'Category',
                'Type',
                'Amount',
                'Reference Number',
                'Notes',
                'Created By',
                'Status'
            ]);

            // Add data rows
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->transaction_date->format('Y-m-d'),
                    $transaction->description,
                    $transaction->category,
                    $transaction->type === 'credit' ? 'Income' : 'Expense',
                    number_format($transaction->amount, 2),
                    $transaction->reference_number,
                    $transaction->notes,
                    $transaction->user->name,
                    ucfirst($transaction->status)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
