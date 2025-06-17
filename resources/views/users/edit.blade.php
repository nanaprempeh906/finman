<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Team Member') }}: {{ $user->name }}
            </h2>
            <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Team
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('users.update', $user) }}">
                        @csrf
                        @method('PATCH')

                        <!-- Name -->
                        <div class="mb-6">
                            <x-input-label for="name" :value="__('Full Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div class="mb-6">
                            <x-input-label for="email" :value="__('Email Address')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        @if(Auth::user()->isAdmin() && Auth::id() !== $user->id)
                            <!-- Role (Admin only for other users) -->
                            <div class="mb-6">
                                <x-input-label for="role" :value="__('Role')" />
                                <select id="role" name="role" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Team Member</option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrator</option>
                                </select>
                                <x-input-error :messages="$errors->get('role')" class="mt-2" />
                                <div class="mt-2 text-sm text-gray-600">
                                    <div class="space-y-2">
                                        <div class="flex items-start">
                                            <span class="inline-block w-2 h-2 mt-2 mr-2 bg-gray-400 rounded-full"></span>
                                            <div>
                                                <strong>Team Member:</strong> Can view financial data, add transactions, and access analytics.
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <span class="inline-block w-2 h-2 mt-2 mr-2 bg-blue-500 rounded-full"></span>
                                            <div>
                                                <strong>Administrator:</strong> Full access including company settings, user management, and all financial data.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status (Admin only for other users) -->
                            <div class="mb-6">
                                <div class="flex items-center">
                                    <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                        Active Account
                                    </label>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Inactive users cannot log in to the system.</p>
                                <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                            </div>
                        @else
                            <!-- Show current role for non-admins or self-editing -->
                            <div class="mb-6">
                                <x-input-label :value="__('Current Role')" />
                                <div class="mt-1 p-3 bg-gray-50 rounded-md">
                                    @if($user->role === 'admin')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Administrator
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Team Member
                                        </span>
                                    @endif
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Contact an administrator to change your role.</p>
                            </div>
                        @endif

                        <!-- Password (Optional) -->
                        <div class="mb-6">
                            <x-input-label for="password" :value="__('New Password (Optional)')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-500">Leave blank to keep current password.</p>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-6" id="password_confirmation_field" style="display: none;">
                            <x-input-label for="password_confirmation" :value="__('Confirm New Password')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <!-- Account Info -->
                        <div class="bg-gray-50 border border-gray-200 rounded-md p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-gray-800">
                                        Account Information
                                    </h3>
                                    <div class="mt-2 text-sm text-gray-600">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li><strong>Member since:</strong> {{ $user->created_at->format('M d, Y') }}</li>
                                            <li><strong>Last updated:</strong> {{ $user->updated_at->format('M d, Y g:i A') }}</li>
                                            <li><strong>Email verified:</strong> {{ $user->email_verified_at ? 'Yes' : 'No' }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-400 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Update Team Member') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Show/hide password confirmation field based on whether password is being changed
        document.getElementById('password').addEventListener('input', function() {
            const confirmField = document.getElementById('password_confirmation_field');
            if (this.value.length > 0) {
                confirmField.style.display = 'block';
                document.getElementById('password_confirmation').required = true;
            } else {
                confirmField.style.display = 'none';
                document.getElementById('password_confirmation').required = false;
                document.getElementById('password_confirmation').value = '';
            }
        });
    </script>
</x-app-layout>
