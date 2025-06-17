<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;

        // Get basic financial metrics
        $totalIncome = $company->getTotalIncome();
        $totalExpenses = $company->getTotalExpenses();
        $currentBalance = $company->getCurrentBalance();
        $profit = $company->getProfit();

        // Get monthly data for charts
        $monthlyIncome = Transaction::forCompany($company->id)
            ->income()
            ->completed()
            ->currentYear()
            ->selectRaw('MONTH(transaction_date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month');

        $monthlyExpenses = Transaction::forCompany($company->id)
            ->expenses()
            ->completed()
            ->currentYear()
            ->selectRaw('MONTH(transaction_date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month');

        // Get recent transactions
        $recentTransactions = Transaction::forCompany($company->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get category breakdown
        $categoryBreakdown = Transaction::forCompany($company->id)
            ->completed()
            ->selectRaw('category, type, SUM(amount) as total')
            ->groupBy('category', 'type')
            ->get()
            ->groupBy('category');

        // Calculate monthly burn rate (last 3 months average expenses)
        $burnRate = Transaction::forCompany($company->id)
            ->expenses()
            ->completed()
            ->where('transaction_date', '>=', Carbon::now()->subMonths(3))
            ->avg('amount');

        // Calculate runway (months company can survive with current balance)
        $runway = $burnRate > 0 ? round($currentBalance / $burnRate, 1) : null;

        // Simple DCF calculation (very basic)
        $averageMonthlyRevenue = Transaction::forCompany($company->id)
            ->income()
            ->completed()
            ->where('transaction_date', '>=', Carbon::now()->subMonths(12))
            ->avg('amount');

        $annualRevenue = $averageMonthlyRevenue * 12;
        $valuationMultiple = 3; // Simple 3x revenue multiple
        $simpleValuation = $annualRevenue * $valuationMultiple;

        return view('dashboard', compact(
            'company',
            'totalIncome',
            'totalExpenses',
            'currentBalance',
            'profit',
            'monthlyIncome',
            'monthlyExpenses',
            'recentTransactions',
            'categoryBreakdown',
            'burnRate',
            'runway',
            'simpleValuation'
        ));
    }
}
