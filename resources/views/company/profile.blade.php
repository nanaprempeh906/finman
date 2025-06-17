<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Company Profile') }} - {{ $company->name }}
            </h2>
            @if(Auth::user()->isAdmin())
                <a href="{{ route('company.edit') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Profile
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Company Overview Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-center">
                                <!-- Company Logo -->
                                <div class="mb-6">
                                    @if($company->logo)
                                        <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }} Logo" class="mx-auto h-24 w-24 rounded-lg object-cover border-2 border-gray-200">
                                    @else
                                        <div class="mx-auto h-24 w-24 rounded-lg bg-indigo-100 flex items-center justify-center border-2 border-gray-200">
                                            <span class="text-2xl font-bold text-indigo-800">
                                                {{ strtoupper(substr($company->name, 0, 2)) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $company->name }}</h3>

                                <!-- Subscription Status -->
                                <div class="mb-4">
                                    @if($company->isOnTrial())
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Trial ({{ $company->trial_ends_at->diffForHumans() }})
                                        </span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            {{ ucfirst($company->subscription_status) }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Company Colors Preview -->
                                @if($company->primary_color || $company->secondary_color)
                                    <div class="mb-4">
                                        <p class="text-sm text-gray-500 mb-2">Brand Colors</p>
                                        <div class="flex justify-center space-x-2">
                                            @if($company->primary_color)
                                                <div class="w-8 h-8 rounded-full border-2 border-gray-300" style="background-color: {{ $company->primary_color }}"></div>
                                            @endif
                                            @if($company->secondary_color)
                                                <div class="w-8 h-8 rounded-full border-2 border-gray-300" style="background-color: {{ $company->secondary_color }}"></div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('company.edit') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Edit Company Profile
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Company Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Company Information</h3>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Company Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $company->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Company Slug</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $company->slug }}</dd>
                                </div>
                                @if($company->email)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            <a href="mailto:{{ $company->email }}" class="text-indigo-600 hover:text-indigo-500">
                                                {{ $company->email }}
                                            </a>
                                        </dd>
                                    </div>
                                @endif
                                @if($company->phone)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            <a href="tel:{{ $company->phone }}" class="text-indigo-600 hover:text-indigo-500">
                                                {{ $company->phone }}
                                            </a>
                                        </dd>
                                    </div>
                                @endif
                                @if($company->website)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Website</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            <a href="{{ $company->website }}" target="_blank" class="text-indigo-600 hover:text-indigo-500">
                                                {{ $company->website }}
                                                <svg class="w-3 h-3 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                </svg>
                                            </a>
                                        </dd>
                                    </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $company->created_at->format('M d, Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if($company->is_active)
                                            <span class="text-green-600">✓ Active</span>
                                        @else
                                            <span class="text-red-600">✗ Inactive</span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>

                            @if($company->about)
                                <div class="mt-6">
                                    <dt class="text-sm font-medium text-gray-500 mb-2">About Company</dt>
                                    <dd class="text-sm text-gray-900 bg-gray-50 p-4 rounded-md">{{ $company->about }}</dd>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Subscription & Trial Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Subscription Details</h3>
                            <div class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Subscription Status</dt>
                                    <dd class="mt-1">
                                        @if($company->subscription_status === 'trial')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Trial
                                            </span>
                                        @elseif($company->subscription_status === 'active')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                {{ ucfirst($company->subscription_status) }}
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                @if($company->trial_ends_at)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Trial Ends</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ $company->trial_ends_at->format('M d, Y g:i A') }}
                                            @if($company->isOnTrial())
                                                <span class="text-yellow-600">({{ $company->trial_ends_at->diffForHumans() }})</span>
                                            @endif
                                        </dd>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Financial Overview -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Financial Overview</h3>
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('company.opening-balance') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Set Opening Balance
                                    </a>
                                @endif
                            </div>
                            <div class="space-y-4">
                                @if($company->opening_balance_date)
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-500">Opening Balance</span>
                                        <span class="text-sm text-blue-600 font-medium">${{ number_format($company->opening_balance, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-500">Opening Date</span>
                                        <span class="text-sm text-gray-900">{{ $company->opening_balance_date->format('M d, Y') }}</span>
                                    </div>
                                @else
                                    <div class="text-center py-4 border-2 border-dashed border-gray-300 rounded-lg">
                                        <p class="text-sm text-gray-500 mb-2">No opening balance set</p>
                                        @if(Auth::user()->isAdmin())
                                            <a href="{{ route('company.opening-balance') }}" class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">Set opening balance</a>
                                        @endif
                                    </div>
                                @endif
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Total Income</span>
                                    <span class="text-sm text-green-600 font-medium">${{ number_format($company->getTotalIncome(), 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Total Expenses</span>
                                    <span class="text-sm text-red-600 font-medium">${{ number_format($company->getTotalExpenses(), 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Total Fees</span>
                                    <span class="text-sm text-red-600 font-medium">${{ number_format($company->getTotalFees(), 2) }}</span>
                                </div>
                                <div class="border-t pt-4">
                                    <div class="flex justify-between">
                                        <span class="text-base font-medium text-gray-900">Current Balance</span>
                                        <div class="text-2xl font-bold {{ $company->getCurrentBalance() >= 0 ? 'text-green-600' : 'text-red-600' }}">${{ number_format($company->getCurrentBalance(), 2) }}</div>
                                    </div>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Available Balance</span>
                                    <span class="text-sm {{ $company->getAvailableBalance() >= 0 ? 'text-green-600' : 'text-red-600' }} font-medium">${{ number_format($company->getAvailableBalance(), 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Company Statistics -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Company Statistics</h3>
                            <div class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-3">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-indigo-600">{{ $company->users()->count() }}</div>
                                    <div class="text-sm text-gray-500">Team Members</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-green-600">{{ $company->transactions()->count() }}</div>
                                    <div class="text-sm text-gray-500">Transactions</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-orange-600">{{ $company->transactions()->where('status', 'pending')->count() }}</div>
                                    <div class="text-sm text-gray-500">Pending Transactions</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
