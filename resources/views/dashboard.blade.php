<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Financial Dashboard') }} - {{ $company->name }}
                </h2>
                <div class="flex items-center mt-1">
                    <span class="text-sm text-gray-600">Welcome back, {{ $currentUser->name }}</span>
                    @if($currentUser->isAdmin())
                        <span class="ml-2 bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                            Administrator
                        </span>
                    @else
                        <span class="ml-2 bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">
                            Team Member
                        </span>
                    @endif
                </div>
            </div>
            <div class="text-sm text-gray-600">
                @if($company->isOnTrial())
                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">
                        Trial ends {{ $company->trial_ends_at->diffForHumans() }}
                    </span>
                @else
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">
                        {{ ucfirst($company->subscription_status) }}
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <!-- Everyone can add transactions -->
                        <a href="{{ route('transactions.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Transaction
                        </a>

                        <!-- Accounts Management -->
                        <a href="{{ route('accounts.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            Manage Accounts
                        </a>

                        <!-- Admin-only actions -->
                        @if($currentUser->isAdmin())
                            <a href="{{ route('company.profile') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Company Settings
                            </a>
                            <a href="{{ route('users.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                                </svg>
                                Manage Team
                            </a>
                        @endif

                        <!-- Everyone can view analytics -->
                        <a href="{{ route('analytics') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            View Analytics
                        </a>
                    </div>
                </div>
            </div>

            <!-- Financial Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Income -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Total Income</h3>
                                <p class="text-2xl font-semibold text-gray-900">{{ $company->formatAmount($totalIncome) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Expenses -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-red-100 rounded-lg">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Total Expenses</h3>
                                <p class="text-2xl font-semibold text-gray-900">{{ $company->formatAmount($totalExpenses) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Balance -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16l3-1m0 0l-3-9"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Total Balance</h3>
                                <p class="text-2xl font-semibold {{ $currentBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $company->formatAmount($currentBalance) }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">Across all accounts</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Company Valuation -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Estimated Valuation</h3>
                                <p class="text-2xl font-semibold text-gray-900">{{ $company->formatAmount($simpleValuation) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accounts Overview -->
            @if($accounts->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Account Overview</h3>
                        <a href="{{ route('accounts.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                            Manage accounts →
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($accounts as $account)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-medium text-gray-900">{{ $account->name }}</h4>
                                    <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                        {{ $account->currency }}
                                    </span>
                                </div>
                                <div class="space-y-1">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Current Balance:</span>
                                        <span class="font-medium {{ $account->current_balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $account->formatAmount($account->current_balance) }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Available:</span>
                                        <span class="font-medium text-gray-900">
                                            {{ $account->formatAmount($account->getAvailableBalance()) }}
                                        </span>
                                    </div>
                                    @if($account->description)
                                        <p class="text-xs text-gray-500 mt-2">{{ Str::limit($account->description, 50) }}</p>
                                    @endif
                                </div>
                                <div class="mt-3 pt-3 border-t border-gray-100">
                                    <a href="{{ route('accounts.show', $account) }}" class="text-xs text-indigo-600 hover:text-indigo-800">
                                        View details →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($accountsByCurrency->count() > 1)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Balance by Currency</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach($accountsByCurrency as $currency => $currencyAccounts)
                                    @php
                                        $totalBalance = $currencyAccounts->sum('current_balance');
                                        $symbols = [
                                            'USD' => '$', 'GHS' => '₵', 'EUR' => '€', 'GBP' => '£',
                                            'NGN' => '₦', 'ZAR' => 'R', 'KES' => 'KSh'
                                        ];
                                        $symbol = $symbols[$currency] ?? $currency . ' ';
                                    @endphp
                                    <div class="text-center">
                                        <div class="text-lg font-semibold {{ $totalBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $symbol }}{{ number_format($totalBalance, 2) }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $currency }}</div>
                                        <div class="text-xs text-gray-400">{{ $currencyAccounts->count() }} account{{ $currencyAccounts->count() > 1 ? 's' : '' }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No accounts yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating your first financial account.</p>
                    <div class="mt-6">
                        <a href="{{ route('accounts.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Create Account
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Additional Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Burn Rate -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Burn Rate</h3>
                        <p class="text-3xl font-bold text-orange-600">{{ $company->formatAmount($burnRate ?? 0) }}</p>
                        <p class="text-sm text-gray-500 mt-1">Per month average</p>
                    </div>
                </div>

                <!-- Runway -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Runway</h3>
                        <p class="text-3xl font-bold text-indigo-600">
                            {{ $runway ? $runway . ' months' : 'N/A' }}
                        </p>
                        <p class="text-sm text-gray-500 mt-1">At current burn rate</p>
                    </div>
                </div>

                <!-- Profit -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Net Profit</h3>
                        <p class="text-3xl font-bold {{ $profit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $company->formatAmount($profit) }}
                        </p>
                        <p class="text-sm text-gray-500 mt-1">Total to date</p>
                    </div>
                </div>
            </div>

                    <!-- Recent Transactions -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Recent Transactions</h3>
                    <a href="{{ route('transactions.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                        View all →
                    </a>
                </div>

                @if($recentTransactions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentTransactions as $transaction)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $transaction->transaction_date->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ Str::limit($transaction->description, 30) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $transaction->account->name ?? 'N/A' }}
                                            <span class="text-xs text-gray-400">({{ $transaction->account->currency ?? 'USD' }})</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ ucfirst($transaction->category) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $transaction->type === 'credit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $transaction->type === 'credit' ? 'Income' : 'Expense' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium
                                            {{ $transaction->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $transaction->type === 'credit' ? '+' : '-' }}{{ $transaction->account ? $transaction->account->formatAmount($transaction->amount) : '$' . number_format($transaction->amount, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No transactions yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by adding your first transaction.</p>
                            <div class="mt-6">
                                <a href="{{ route('transactions.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    Add Transaction
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
