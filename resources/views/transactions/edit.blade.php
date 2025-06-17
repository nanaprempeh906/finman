<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Transaction') }}
            </h2>
            <a href="{{ route('transactions.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Transactions
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('transactions.update', $transaction) }}" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <!-- Transaction Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Transaction Type</label>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <input type="radio" id="credit" name="type" value="credit"
                                           {{ old('type', $transaction->type) === 'credit' ? 'checked' : '' }}
                                           class="hidden peer" required>
                                    <label for="credit" class="flex items-center justify-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-green-500 peer-checked:bg-green-50">
                                        <div class="text-center">
                                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                            </div>
                                            <span class="text-sm font-medium text-gray-900">Income</span>
                                            <p class="text-xs text-gray-500">Money coming in</p>
                                        </div>
                                    </label>
                                </div>

                                <div>
                                    <input type="radio" id="debit" name="type" value="debit"
                                           {{ old('type', $transaction->type) === 'debit' ? 'checked' : '' }}
                                           class="hidden peer" required>
                                    <label for="debit" class="flex items-center justify-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-red-500 peer-checked:bg-red-50">
                                        <div class="text-center">
                                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                            </div>
                                            <span class="text-sm font-medium text-gray-900">Expense</span>
                                            <p class="text-xs text-gray-500">Money going out</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <!-- Basic Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Description -->
                            <div class="md:col-span-2">
                                <x-input-label for="description" :value="__('Description')" />
                                <x-text-input id="description" name="description" type="text" class="mt-1 block w-full"
                                             :value="old('description', $transaction->description)" required autofocus
                                             placeholder="Enter transaction description..." />
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <!-- Amount -->
                            <div>
                                <x-input-label for="amount" :value="__('Amount')" />
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <x-text-input id="amount" name="amount" type="number" step="0.01" min="0.01"
                                                 class="pl-7 block w-full" :value="old('amount', $transaction->amount)" required
                                                 placeholder="0.00" />
                                </div>
                                <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                            </div>

                            <!-- Transaction Date -->
                            <div>
                                <x-input-label for="transaction_date" :value="__('Transaction Date')" />
                                <x-text-input id="transaction_date" name="transaction_date" type="date"
                                             class="mt-1 block w-full" :value="old('transaction_date', $transaction->transaction_date->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('transaction_date')" class="mt-2" />
                            </div>

                            <!-- Payment Method -->
                            <div>
                                <x-input-label for="method" :value="__('Payment Method')" />
                                <select id="method" name="method" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Select payment method</option>
                                    <option value="cash" {{ old('method', $transaction->method) === 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="bank_transfer" {{ old('method', $transaction->method) === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="credit_card" {{ old('method', $transaction->method) === 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                    <option value="debit_card" {{ old('method', $transaction->method) === 'debit_card' ? 'selected' : '' }}>Debit Card</option>
                                    <option value="check" {{ old('method', $transaction->method) === 'check' ? 'selected' : '' }}>Check</option>
                                    <option value="digital_wallet" {{ old('method', $transaction->method) === 'digital_wallet' ? 'selected' : '' }}>Digital Wallet</option>
                                    <option value="other" {{ old('method', $transaction->method) === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                <x-input-error :messages="$errors->get('method')" class="mt-2" />
                            </div>

                            <!-- Category -->
                            <div>
                                <x-input-label for="category" :value="__('Category')" />
                                <select id="category" name="category" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Select a category</option>
                                    @foreach($categories as $value => $label)
                                        <option value="{{ $value }}" {{ old('category', $transaction->category) === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('category')" class="mt-2" />
                            </div>

                            <!-- Transaction Fee -->
                            <div>
                                <x-input-label for="fee" :value="__('Transaction Fee (Optional)')" />
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <x-text-input id="fee" name="fee" type="number" step="0.01" min="0"
                                                 class="pl-7 block w-full" :value="old('fee', $transaction->fee)"
                                                 placeholder="0.00" />
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Processing fees, bank charges, etc.</p>
                                <x-input-error :messages="$errors->get('fee')" class="mt-2" />
                            </div>

                            <!-- Reference Number -->
                            <div>
                                <x-input-label for="reference_number" :value="__('Reference Number (Optional)')" />
                                <x-text-input id="reference_number" name="reference_number" type="text"
                                             class="mt-1 block w-full" :value="old('reference_number', $transaction->reference_number)"
                                             placeholder="Invoice #, Receipt #, etc." />
                                <x-input-error :messages="$errors->get('reference_number')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <x-input-label for="notes" :value="__('Notes (Optional)')" />
                            <textarea id="notes" name="notes" rows="3"
                                     class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                     placeholder="Additional notes about this transaction...">{{ old('notes', $transaction->notes) }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                            <a href="{{ route('transactions.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <x-primary-button>
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Update Transaction
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
