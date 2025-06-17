<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Set Opening Balance') }}
            </h2>
            <a href="{{ route('company.profile') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Company Profile
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Company Opening Balance</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Set your company's opening balance to accurately track your financial position. This should be the amount you had when you started using this system.
                        </p>
                    </div>

                    @if($company->opening_balance_date)
                        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-blue-800 font-medium">Current Opening Balance: ${{ number_format($company->opening_balance, 2) }}</p>
                                    <p class="text-blue-700 text-sm">Set on {{ $company->opening_balance_date->format('F j, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('company.opening-balance.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Opening Balance Amount -->
                            <div>
                                <x-input-label for="opening_balance" :value="__('Opening Balance Amount')" />
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <x-text-input id="opening_balance" name="opening_balance" type="number" step="0.01"
                                                 class="pl-7 block w-full" :value="old('opening_balance', $company->opening_balance)"
                                                 placeholder="0.00" required autofocus />
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Enter your company's cash/bank balance when you started using this system</p>
                                <x-input-error :messages="$errors->get('opening_balance')" class="mt-2" />
                            </div>

                            <!-- Opening Balance Date -->
                            <div>
                                <x-input-label for="opening_balance_date" :value="__('Opening Balance Date')" />
                                <x-text-input id="opening_balance_date" name="opening_balance_date" type="date"
                                             class="mt-1 block w-full" :value="old('opening_balance_date', $company->opening_balance_date?->format('Y-m-d') ?? date('Y-m-d'))" required />
                                <p class="mt-1 text-xs text-gray-500">The date when this balance was recorded</p>
                                <x-input-error :messages="$errors->get('opening_balance_date')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Balance Summary -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Balance Summary</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Opening Balance:</span>
                                    <span class="font-medium text-blue-600">${{ number_format($company->opening_balance, 2) }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Total Transactions:</span>
                                    <span class="font-medium text-gray-900">${{ number_format($company->getProfit(), 2) }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Current Balance:</span>
                                    <span class="font-medium {{ $company->getCurrentBalance() >= 0 ? 'text-green-600' : 'text-red-600' }}">${{ number_format($company->getCurrentBalance(), 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                            <a href="{{ route('company.profile') }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <x-primary-button>
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                {{ $company->opening_balance_date ? 'Update' : 'Set' }} Opening Balance
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
