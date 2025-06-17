<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Financial Analytics') }} - {{ $company->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('valuation') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    Company Valuation
                </a>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Key Metrics Overview -->
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
                                <p class="text-2xl font-semibold text-gray-900">${{ number_format($totalIncome, 2) }}</p>
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
                                <p class="text-2xl font-semibold text-gray-900">${{ number_format($totalExpenses, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Net Profit -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16l3-1m0 0l-3-9"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Net Profit</h3>
                                <p class="text-2xl font-semibold {{ $profit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    ${{ number_format($profit, 2) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Growth Rate -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-indigo-100 rounded-lg">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Income Growth</h3>
                                <p class="text-2xl font-semibold {{ $incomeGrowthRate >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($incomeGrowthRate, 1) }}%
                                </p>
                                <p class="text-xs text-gray-500">Month over month</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Trends Chart -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Monthly Financial Trends (Last 12 Months)</h3>
                    <div class="h-96">
                        <canvas id="monthlyTrendsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Category Breakdown -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Income by Category -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Income by Category</h3>
                        @if($incomeByCategory->count() > 0)
                            <div class="space-y-3">
                                @foreach($incomeByCategory as $category)
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-700">
                                            {{ ucfirst(str_replace('_', ' ', $category->category)) }}
                                        </span>
                                        <span class="text-sm font-semibold text-green-600">
                                            ${{ number_format($category->total, 2) }}
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full"
                                             style="width: {{ $totalIncome > 0 ? ($category->total / $totalIncome) * 100 : 0 }}%"></div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">No income transactions found.</p>
                        @endif
                    </div>
                </div>

                <!-- Expenses by Category -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Expenses by Category</h3>
                        @if($expensesByCategory->count() > 0)
                            <div class="space-y-3">
                                @foreach($expensesByCategory as $category)
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-700">
                                            {{ ucfirst(str_replace('_', ' ', $category->category)) }}
                                        </span>
                                        <span class="text-sm font-semibold text-red-600">
                                            ${{ number_format($category->total, 2) }}
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-red-600 h-2 rounded-full"
                                             style="width: {{ $totalExpenses > 0 ? ($category->total / $totalExpenses) * 100 : 0 }}%"></div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">No expense transactions found.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Additional Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Average Income Transaction -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-sm font-medium text-gray-500">Avg Income Transaction</h3>
                        <p class="text-2xl font-semibold text-green-600">
                            ${{ number_format($avgIncomeTransaction ?? 0, 2) }}
                        </p>
                    </div>
                </div>

                <!-- Average Expense Transaction -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-sm font-medium text-gray-500">Avg Expense Transaction</h3>
                        <p class="text-2xl font-semibold text-red-600">
                            ${{ number_format($avgExpenseTransaction ?? 0, 2) }}
                        </p>
                    </div>
                </div>

                <!-- Total Transactions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-sm font-medium text-gray-500">Total Transactions</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalTransactions) }}</p>
                    </div>
                </div>

                <!-- Monthly Transaction Rate -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-sm font-medium text-gray-500">Avg Monthly Transactions</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($avgTransactionsPerMonth, 1) }}</p>
                    </div>
                </div>
            </div>

            <!-- Burn Rate and Runway -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Burn Rate -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Monthly Burn Rate</h3>
                        <p class="text-3xl font-bold text-orange-600">${{ number_format($burnRate ?? 0, 2) }}</p>
                        <p class="text-sm text-gray-500 mt-1">Average monthly expenses (last 3 months)</p>
                        <div class="mt-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Sustainability</span>
                                <span class="font-medium {{ ($burnRate ?? 0) < ($totalIncome / 12) ? 'text-green-600' : 'text-red-600' }}">
                                    {{ ($burnRate ?? 0) < ($totalIncome / 12) ? 'Sustainable' : 'High Risk' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Runway -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Cash Runway</h3>
                        <p class="text-3xl font-bold text-indigo-600">
                            {{ $runway ? $runway . ' months' : 'N/A' }}
                        </p>
                        <p class="text-sm text-gray-500 mt-1">At current burn rate</p>
                        <div class="mt-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Status</span>
                                <span class="font-medium {{ ($runway ?? 0) > 6 ? 'text-green-600' : (($runway ?? 0) > 3 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ ($runway ?? 0) > 6 ? 'Healthy' : (($runway ?? 0) > 3 ? 'Caution' : 'Critical') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Monthly Trends Chart
        const ctx = document.getElementById('monthlyTrendsChart').getContext('2d');
        const monthlyData = @json($monthlyData);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthlyData.map(item => item.month),
                datasets: [
                    {
                        label: 'Income',
                        data: monthlyData.map(item => item.income),
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Expenses',
                        data: monthlyData.map(item => item.expenses),
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Profit',
                        data: monthlyData.map(item => item.profit),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Financial Performance Over Time'
                    },
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    </script>
</x-app-layout>
