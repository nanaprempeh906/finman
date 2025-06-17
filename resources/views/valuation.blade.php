<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Company Valuation') }} - {{ $company->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('analytics') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    View Analytics
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
            <!-- Valuation Summary -->
            <div class="bg-gradient-to-r from-purple-600 to-blue-600 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-white">
                    <div class="text-center">
                        <h3 class="text-2xl font-bold mb-2">Estimated Company Valuation</h3>
                        <div class="text-5xl font-bold mb-4">
                            ${{ number_format($valuationRange['average'], 0) }}
                        </div>
                        <div class="flex justify-center space-x-8 text-sm">
                            <div>
                                <span class="text-purple-200">Low</span>
                                <div class="font-semibold">${{ number_format($valuationRange['low'], 0) }}</div>
                            </div>
                            <div>
                                <span class="text-purple-200">High</span>
                                <div class="font-semibold">${{ number_format($valuationRange['high'], 0) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Key Financial Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-sm font-medium text-gray-500">Annual Revenue</h3>
                        <p class="text-2xl font-semibold text-gray-900">${{ number_format($totalAnnualRevenue, 2) }}</p>
                        <p class="text-sm text-gray-500 mt-1">Last 12 months</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-sm font-medium text-gray-500">Growth Rate</h3>
                        <p class="text-2xl font-semibold {{ $growthRate >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($growthRate, 1) }}%
                        </p>
                        <p class="text-sm text-gray-500 mt-1">6-month comparison</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-sm font-medium text-gray-500">Net Assets</h3>
                        <p class="text-2xl font-semibold {{ $currentBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            ${{ number_format($currentBalance, 2) }}
                        </p>
                        <p class="text-sm text-gray-500 mt-1">Current balance</p>
                    </div>
                </div>
            </div>

            <!-- Valuation Methods -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Revenue Multiple Method -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Revenue Multiple Valuation</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-red-50 rounded-lg">
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Conservative (2x)</span>
                                    <p class="text-xs text-gray-500">Low-growth, stable businesses</p>
                                </div>
                                <span class="text-lg font-semibold text-gray-900">
                                    ${{ number_format($revenueValuations['conservative'], 0) }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Moderate (4x)</span>
                                    <p class="text-xs text-gray-500">Average market conditions</p>
                                </div>
                                <span class="text-lg font-semibold text-gray-900">
                                    ${{ number_format($revenueValuations['moderate'], 0) }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Aggressive (8x)</span>
                                    <p class="text-xs text-gray-500">High-growth, tech companies</p>
                                </div>
                                <span class="text-lg font-semibold text-gray-900">
                                    ${{ number_format($revenueValuations['aggressive'], 0) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DCF Method -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Discounted Cash Flow (DCF)</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Projected Growth Rate:</span>
                                <span class="font-medium">{{ number_format($projectedGrowthRate * 100, 1) }}%</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Discount Rate:</span>
                                <span class="font-medium">{{ number_format($discountRate * 100, 1) }}%</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Terminal Value:</span>
                                <span class="font-medium">${{ number_format($terminalValue, 0) }}</span>
                            </div>
                            <div class="border-t pt-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-medium text-gray-700">DCF Valuation:</span>
                                    <span class="text-xl font-bold text-blue-600">
                                        ${{ number_format($dcfValuation, 0) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Market Comparables -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Market Comparables</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Industry Multiple:</span>
                                <span class="font-medium">6x Revenue</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Annual Revenue:</span>
                                <span class="font-medium">${{ number_format($totalAnnualRevenue, 0) }}</span>
                            </div>
                            <div class="border-t pt-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-medium text-gray-700">Market Valuation:</span>
                                    <span class="text-xl font-bold text-green-600">
                                        ${{ number_format($marketComparableValuation, 0) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Asset-Based -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Asset-Based Valuation</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Current Assets:</span>
                                <span class="font-medium">${{ number_format(max($currentBalance, 0), 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Intangible Assets:</span>
                                <span class="font-medium">Not calculated</span>
                            </div>
                            <div class="border-t pt-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-medium text-gray-700">Asset Valuation:</span>
                                    <span class="text-xl font-bold text-purple-600">
                                        ${{ number_format($assetBasedValuation, 0) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Projected Cash Flows -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">5-Year Cash Flow Projections</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Projected Cash Flow</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Present Value</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount Factor</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($projectedCashFlows as $projection)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            Year {{ $projection['year'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            ${{ number_format($projection['cash_flow'], 0) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">
                                            ${{ number_format($projection['present_value'], 0) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ number_format(1 / pow(1 + $discountRate, $projection['year']), 3) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Revenue Trends -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Monthly Revenue Trends</h3>
                    <div class="h-80">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Valuation Summary -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Valuation Summary</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-700 mb-3">Key Assumptions</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Growth rate based on 6-month trend analysis</li>
                                <li>• 12% discount rate (industry standard)</li>
                                <li>• 3% terminal growth rate</li>
                                <li>• Revenue multiples based on industry benchmarks</li>
                                <li>• Asset valuation excludes intangible assets</li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-700 mb-3">Recommendations</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                @if($growthRate > 10)
                                    <li class="text-green-600">• Strong growth trajectory supports higher valuations</li>
                                @elseif($growthRate > 0)
                                    <li class="text-yellow-600">• Moderate growth - focus on efficiency improvements</li>
                                @else
                                    <li class="text-red-600">• Negative growth - urgent strategic review needed</li>
                                @endif

                                @if($currentBalance > ($totalAnnualRevenue * 0.5))
                                    <li class="text-green-600">• Strong cash position provides stability</li>
                                @else
                                    <li class="text-yellow-600">• Consider improving cash management</li>
                                @endif

                                <li>• Regular valuation updates recommended</li>
                                <li>• Consider professional valuation for major decisions</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Revenue Trends Chart
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const monthlyRevenue = @json($monthlyRevenue);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthlyRevenue.map(item => item.month),
                datasets: [{
                    label: 'Monthly Revenue',
                    data: monthlyRevenue.map(item => item.revenue),
                    borderColor: 'rgb(147, 51, 234)',
                    backgroundColor: 'rgba(147, 51, 234, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Revenue Performance Over Time'
                    },
                    legend: {
                        display: false
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
                }
            }
        });
    </script>
</x-app-layout>
