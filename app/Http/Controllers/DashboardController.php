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

        // Get basic financial metrics across all accounts
        $totalIncome = $company->getTotalIncome();
        $totalExpenses = $company->getTotalExpenses();
        $totalFees = $company->getTotalFees();
        $currentBalance = $company->getTotalCurrentBalance();
        $availableBalance = $company->getTotalAvailableBalance();
        $profit = $company->getProfit();

        // Get accounts summary
        $accounts = $company->accounts()->active()->get();
        $accountsByCurrency = $company->getAccountsByCurrency();

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
            ->with(['user', 'account'])
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
            'totalFees',
            'currentBalance',
            'availableBalance',
            'profit',
            'accounts',
            'accountsByCurrency',
            'monthlyIncome',
            'monthlyExpenses',
            'recentTransactions',
            'categoryBreakdown',
            'burnRate',
            'runway',
            'simpleValuation'
        ))->with('currentUser', $user);
    }

    /**
     * Show detailed analytics page
     */
    public function analytics()
    {
        $user = Auth::user();
        $company = $user->company;

        // Basic financial metrics
        $totalIncome = $company->getTotalIncome();
        $totalExpenses = $company->getTotalExpenses();
        $currentBalance = $company->getTotalCurrentBalance();
        $profit = $company->getProfit();

        // Monthly data for the last 12 months
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $monthName = $date->format('M Y');

            $monthlyIncome = Transaction::forCompany($company->id)
                ->income()
                ->completed()
                ->whereYear('transaction_date', $date->year)
                ->whereMonth('transaction_date', $date->month)
                ->sum('amount');

            $monthlyExpenses = Transaction::forCompany($company->id)
                ->expenses()
                ->completed()
                ->whereYear('transaction_date', $date->year)
                ->whereMonth('transaction_date', $date->month)
                ->sum('amount');

            $monthlyData[] = [
                'month' => $monthName,
                'income' => $monthlyIncome,
                'expenses' => $monthlyExpenses,
                'profit' => $monthlyIncome - $monthlyExpenses
            ];
        }

        // Category breakdown
        $incomeByCategory = Transaction::forCompany($company->id)
            ->income()
            ->completed()
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->get();

        $expensesByCategory = Transaction::forCompany($company->id)
            ->expenses()
            ->completed()
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->get();

        // Growth metrics
        $currentMonthIncome = Transaction::forCompany($company->id)
            ->income()
            ->completed()
            ->whereMonth('transaction_date', Carbon::now()->month)
            ->whereYear('transaction_date', Carbon::now()->year)
            ->sum('amount');

        $lastMonthIncome = Transaction::forCompany($company->id)
            ->income()
            ->completed()
            ->whereMonth('transaction_date', Carbon::now()->subMonth()->month)
            ->whereYear('transaction_date', Carbon::now()->subMonth()->year)
            ->sum('amount');

        $incomeGrowthRate = $lastMonthIncome > 0
            ? (($currentMonthIncome - $lastMonthIncome) / $lastMonthIncome) * 100
            : 0;

        // Average transaction values
        $avgIncomeTransaction = Transaction::forCompany($company->id)
            ->income()
            ->completed()
            ->avg('amount');

        $avgExpenseTransaction = Transaction::forCompany($company->id)
            ->expenses()
            ->completed()
            ->avg('amount');

        // Transaction frequency
        $totalTransactions = Transaction::forCompany($company->id)->completed()->count();
        $avgTransactionsPerMonth = $totalTransactions > 0 ? $totalTransactions / 12 : 0;

        // Calculate burn rate and runway
        $burnRate = Transaction::forCompany($company->id)
            ->expenses()
            ->completed()
            ->where('transaction_date', '>=', Carbon::now()->subMonths(3))
            ->avg('amount');

        $runway = $burnRate > 0 ? round($currentBalance / $burnRate, 1) : null;

        return view('analytics', compact(
            'company',
            'totalIncome',
            'totalExpenses',
            'currentBalance',
            'profit',
            'monthlyData',
            'incomeByCategory',
            'expensesByCategory',
            'incomeGrowthRate',
            'avgIncomeTransaction',
            'avgExpenseTransaction',
            'totalTransactions',
            'avgTransactionsPerMonth',
            'burnRate',
            'runway'
        ));
    }

    /**
     * Show detailed valuation page
     */
    public function valuation()
    {
        $user = Auth::user();
        $company = $user->company;

        // Basic financial metrics
        $totalIncome = $company->getTotalIncome();
        $totalExpenses = $company->getTotalExpenses();
        $currentBalance = $company->getCurrentBalance();

        // Revenue metrics for valuation
        $monthlyRevenue = [];
        $totalAnnualRevenue = 0;

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyIncome = Transaction::forCompany($company->id)
                ->income()
                ->completed()
                ->whereYear('transaction_date', $date->year)
                ->whereMonth('transaction_date', $date->month)
                ->sum('amount');

            $monthlyRevenue[] = [
                'month' => $date->format('M Y'),
                'revenue' => $monthlyIncome
            ];

            $totalAnnualRevenue += $monthlyIncome;
        }

        // Calculate growth rate
        $recentMonths = array_slice($monthlyRevenue, -6); // Last 6 months
        $earlierMonths = array_slice($monthlyRevenue, 0, 6); // First 6 months

        $recentAverage = collect($recentMonths)->avg('revenue');
        $earlierAverage = collect($earlierMonths)->avg('revenue');

        $growthRate = $earlierAverage > 0
            ? (($recentAverage - $earlierAverage) / $earlierAverage) * 100
            : 0;

        // Different valuation methods

        // 1. Revenue Multiple Method
        $revenueMultiples = [
            'conservative' => 2,
            'moderate' => 4,
            'aggressive' => 8
        ];

        $revenueValuations = [];
        foreach ($revenueMultiples as $type => $multiple) {
            $revenueValuations[$type] = $totalAnnualRevenue * $multiple;
        }

        // 2. Discounted Cash Flow (Simplified)
        $projectedGrowthRate = max($growthRate / 100, 0.05); // Minimum 5% growth
        $discountRate = 0.12; // 12% discount rate
        $terminalGrowthRate = 0.03; // 3% terminal growth

        $projectedCashFlows = [];
        $currentCashFlow = $totalIncome - $totalExpenses;

        for ($year = 1; $year <= 5; $year++) {
            $projectedCashFlow = $currentCashFlow * pow(1 + $projectedGrowthRate, $year);
            $presentValue = $projectedCashFlow / pow(1 + $discountRate, $year);
            $projectedCashFlows[] = [
                'year' => $year,
                'cash_flow' => $projectedCashFlow,
                'present_value' => $presentValue
            ];
        }

        $terminalValue = ($projectedCashFlows[4]['cash_flow'] * (1 + $terminalGrowthRate)) / ($discountRate - $terminalGrowthRate);
        $terminalPresentValue = $terminalValue / pow(1 + $discountRate, 5);

        $dcfValuation = collect($projectedCashFlows)->sum('present_value') + $terminalPresentValue;

        // 3. Asset-based valuation (simplified)
        $assetBasedValuation = max($currentBalance, 0); // Simplified - just current balance

        // 4. Market comparables (estimated)
        $industryMultiple = 6; // Assumed industry multiple
        $marketComparableValuation = $totalAnnualRevenue * $industryMultiple;

        // Average valuation
        $valuations = [
            $revenueValuations['moderate'],
            $dcfValuation,
            $marketComparableValuation
        ];

        $averageValuation = collect($valuations)->average();

        // Valuation range
        $valuationRange = [
            'low' => min($revenueValuations['conservative'], $assetBasedValuation),
            'high' => max($revenueValuations['aggressive'], $dcfValuation),
            'average' => $averageValuation
        ];

        return view('valuation', compact(
            'company',
            'totalIncome',
            'totalExpenses',
            'currentBalance',
            'totalAnnualRevenue',
            'monthlyRevenue',
            'growthRate',
            'revenueValuations',
            'dcfValuation',
            'assetBasedValuation',
            'marketComparableValuation',
            'valuationRange',
            'projectedCashFlows',
            'terminalValue',
            'projectedGrowthRate',
            'discountRate'
        ));
    }
}
