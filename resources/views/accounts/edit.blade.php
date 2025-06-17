<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Account: ') . $account->name }}
            </h2>
            <a href="{{ route('accounts.show', $account) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Account
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('accounts.update', $account) }}">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Account Name -->
                            <div>
                                <x-input-label for="name" :value="__('Account Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $account->name)" required autofocus placeholder="e.g., Prudential Cedi Account" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                <p class="mt-1 text-sm text-gray-600">Choose a descriptive name for your account</p>
                            </div>

                            <!-- Currency -->
                            <div>
                                <x-input-label for="currency" :value="__('Currency')" />
                                <select id="currency" name="currency" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    <option value="">Select Currency</option>
                                    @foreach($currencies as $code => $name)
                                        <option value="{{ $code }}" {{ (old('currency', $account->currency) === $code) ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('currency')" />
                                <p class="mt-1 text-sm text-gray-600">Select the currency for this account</p>
                            </div>

                            <!-- Opening Balance -->
                            <div>
                                <x-input-label for="opening_balance" :value="__('Opening Balance')" />
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm" id="currency-symbol">{{ $account->currency_symbol }}</span>
                                    </div>
                                    <x-text-input id="opening_balance" name="opening_balance" type="number" step="0.01" min="0" class="pl-8 block w-full" :value="old('opening_balance', $account->opening_balance)" required placeholder="0.00" />
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('opening_balance')" />
                                <p class="mt-1 text-sm text-gray-600">Enter the starting balance for this account</p>
                                @if($account->transactions()->count() > 0)
                                    <p class="mt-1 text-sm text-amber-600">⚠️ Changing opening balance will affect current balance calculations</p>
                                @endif
                            </div>

                            <!-- Account Description -->
                            <div>
                                <x-input-label for="description" :value="__('Description (Optional)')" />
                                <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Additional details about this account...">{{ old('description', $account->description) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                                <p class="mt-1 text-sm text-gray-600">Optional description for this account</p>
                            </div>
                        </div>

                        <!-- Account Status -->
                        <div class="mt-6">
                            <div class="flex items-center">
                                <input id="is_active" name="is_active" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ old('is_active', $account->is_active) ? 'checked' : '' }}>
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    Account is active
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-600">Inactive accounts won't appear in transaction forms</p>
                        </div>

                        <!-- Current Balance Information -->
                        <div class="mt-8 p-4 bg-gray-50 rounded-lg border">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Current Balance Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Current Balance</dt>
                                    <dd class="mt-1 text-sm {{ $account->calculateCurrentBalance() >= 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                                        {{ $account->formatCurrency($account->calculateCurrentBalance()) }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Total Transactions</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $account->transactions()->count() }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Available Balance</dt>
                                    <dd class="mt-1 text-sm {{ $account->getAvailableBalance() >= 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                                        {{ $account->formatCurrency($account->getAvailableBalance()) }}
                                    </dd>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-6 space-x-3">
                            <div class="flex space-x-3">
                                @if($account->transactions()->count() === 0)
                                    <form method="POST" action="{{ route('accounts.destroy', $account) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Are you sure you want to delete this account? This action cannot be undone.')" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Delete Account
                                        </button>
                                    </form>
                                @else
                                    @if($account->is_active)
                                        <form method="POST" action="{{ route('accounts.archive', $account) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" onclick="return confirm('Are you sure you want to archive this account?')" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8l4 4 4-4m0 6l-4-4-4 4"></path>
                                                </svg>
                                                Archive Account
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('accounts.restore', $account) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                </svg>
                                                Restore Account
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>

                            <div class="flex space-x-3">
                                <a href="{{ route('accounts.show', $account) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Cancel
                                </a>
                                <x-primary-button>
                                    {{ __('Update Account') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Currency symbols mapping
        const currencySymbols = {
            'USD': '$',
            'GHS': '₵',
            'EUR': '€',
            'GBP': '£',
            'NGN': '₦',
            'ZAR': 'R',
            'KES': 'KSh'
        };

        // Update currency symbol when currency is changed
        document.getElementById('currency').addEventListener('change', function() {
            const currency = this.value;
            const symbol = currencySymbols[currency] || '$';
            document.getElementById('currency-symbol').textContent = symbol;
        });
    </script>
</x-app-layout>
