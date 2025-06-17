<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create New Account') }}
            </h2>
            <a href="{{ route('accounts.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Accounts
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('accounts.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Account Name -->
                            <div>
                                <x-input-label for="name" :value="__('Account Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus placeholder="e.g., Prudential Cedi Account" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                <p class="mt-1 text-sm text-gray-600">Choose a descriptive name for your account</p>
                            </div>

                            <!-- Currency -->
                            <div>
                                <x-input-label for="currency" :value="__('Currency')" />
                                <select id="currency" name="currency" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    <option value="">Select Currency</option>
                                    @foreach($currencies as $code => $name)
                                        <option value="{{ $code }}" {{ old('currency') === $code ? 'selected' : '' }}>
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
                                        <span class="text-gray-500 sm:text-sm" id="currency-symbol">$</span>
                                    </div>
                                    <x-text-input id="opening_balance" name="opening_balance" type="number" step="0.01" min="0" class="pl-8 block w-full" :value="old('opening_balance', '0.00')" required placeholder="0.00" />
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('opening_balance')" />
                                <p class="mt-1 text-sm text-gray-600">Enter the starting balance for this account</p>
                            </div>

                            <!-- Account Description -->
                            <div>
                                <x-input-label for="description" :value="__('Description (Optional)')" />
                                <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Additional details about this account...">{{ old('description') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                                <p class="mt-1 text-sm text-gray-600">Optional description for this account</p>
                            </div>
                        </div>

                        <!-- Account Preview -->
                        <div class="mt-8 p-4 bg-gray-50 rounded-lg border">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Account Preview</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Account Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900" id="preview-name">-</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Currency</dt>
                                    <dd class="mt-1 text-sm text-gray-900" id="preview-currency">-</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Opening Balance</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-medium" id="preview-balance">-</dd>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-3">
                            <a href="{{ route('accounts.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Create Account') }}
                            </x-primary-button>
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

        // Update currency symbol and preview
        function updateCurrencySymbol() {
            const currency = document.getElementById('currency').value;
            const symbol = currencySymbols[currency] || '$';
            document.getElementById('currency-symbol').textContent = symbol;
            updatePreview();
        }

        // Update preview
        function updatePreview() {
            const name = document.getElementById('name').value || '-';
            const currency = document.getElementById('currency').value || '-';
            const balance = document.getElementById('opening_balance').value || '0.00';
            const currencyText = currency !== '-' ? document.getElementById('currency').options[document.getElementById('currency').selectedIndex].text : '-';
            const symbol = currencySymbols[currency] || '';

            document.getElementById('preview-name').textContent = name;
            document.getElementById('preview-currency').textContent = currencyText;
            document.getElementById('preview-balance').textContent = currency !== '-' ? `${symbol}${parseFloat(balance).toFixed(2)}` : '-';
        }

        // Event listeners
        document.getElementById('currency').addEventListener('change', updateCurrencySymbol);
        document.getElementById('name').addEventListener('input', updatePreview);
        document.getElementById('opening_balance').addEventListener('input', updatePreview);

        // Initial update
        updatePreview();
    </script>
</x-app-layout>
