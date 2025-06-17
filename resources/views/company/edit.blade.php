<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Company Profile') }} - {{ $company->name }}
            </h2>
            <a href="{{ route('company.profile') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Profile
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('company.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <!-- Basic Information Section -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>

                            <!-- Company Name -->
                            <div class="mb-6">
                                <x-input-label for="name" :value="__('Company Name')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $company->name)" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- About Company -->
                            <div class="mb-6">
                                <x-input-label for="about" :value="__('About Company')" />
                                <textarea id="about" name="about" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Tell us about your company...">{{ old('about', $company->about) }}</textarea>
                                <x-input-error :messages="$errors->get('about')" class="mt-2" />
                                <p class="mt-1 text-sm text-gray-500">Brief description of your company (max 1000 characters)</p>
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Email -->
                                <div>
                                    <x-input-label for="email" :value="__('Company Email')" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $company->email)" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <!-- Phone -->
                                <div>
                                    <x-input-label for="phone" :value="__('Phone Number')" />
                                    <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone', $company->phone)" />
                                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                </div>

                                <!-- Website -->
                                <div class="md:col-span-2">
                                    <x-input-label for="website" :value="__('Website URL')" />
                                    <x-text-input id="website" class="block mt-1 w-full" type="url" name="website" :value="old('website', $company->website)" placeholder="https://example.com" />
                                    <x-input-error :messages="$errors->get('website')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Branding Section -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Branding & Design</h3>

                            <!-- Logo Upload -->
                            <div class="mb-6">
                                <x-input-label for="logo" :value="__('Company Logo')" />

                                <!-- Current Logo Preview -->
                                @if($company->logo)
                                    <div class="mt-2 mb-4">
                                        <p class="text-sm text-gray-600 mb-2">Current Logo:</p>
                                        <img src="{{ asset('storage/' . $company->logo) }}" alt="Current Logo" class="h-20 w-20 object-cover rounded-lg border-2 border-gray-200">
                                    </div>
                                @endif

                                <input id="logo" type="file" name="logo" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                                <p class="mt-1 text-sm text-gray-500">Upload a new logo (JPEG, PNG, JPG, GIF up to 2MB)</p>
                            </div>

                            <!-- Brand Colors -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Primary Color -->
                                <div>
                                    <x-input-label for="primary_color" :value="__('Primary Brand Color')" />
                                    <div class="flex items-center mt-1 space-x-2">
                                        <input id="primary_color" type="color" name="primary_color" value="{{ old('primary_color', $company->primary_color ?? '#3B82F6') }}" class="h-10 w-20 border border-gray-300 rounded cursor-pointer" />
                                        <x-text-input type="text" name="primary_color_text" :value="old('primary_color', $company->primary_color ?? '#3B82F6')" class="flex-1" readonly />
                                    </div>
                                    <x-input-error :messages="$errors->get('primary_color')" class="mt-2" />
                                    <p class="mt-1 text-sm text-gray-500">Used for buttons, links, and primary elements</p>
                                </div>

                                <!-- Secondary Color -->
                                <div>
                                    <x-input-label for="secondary_color" :value="__('Secondary Brand Color')" />
                                    <div class="flex items-center mt-1 space-x-2">
                                        <input id="secondary_color" type="color" name="secondary_color" value="{{ old('secondary_color', $company->secondary_color ?? '#6B7280') }}" class="h-10 w-20 border border-gray-300 rounded cursor-pointer" />
                                        <x-text-input type="text" name="secondary_color_text" :value="old('secondary_color', $company->secondary_color ?? '#6B7280')" class="flex-1" readonly />
                                    </div>
                                    <x-input-error :messages="$errors->get('secondary_color')" class="mt-2" />
                                    <p class="mt-1 text-sm text-gray-500">Used for accents and secondary elements</p>
                                </div>
                            </div>
                        </div>

                        <!-- Subscription Information (Read-only) -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Subscription Information</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Current Status:</span>
                                        <div class="mt-1">
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
                                        </div>
                                    </div>
                                    @if($company->trial_ends_at)
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Trial Ends:</span>
                                            <div class="mt-1 text-sm text-gray-900">
                                                {{ $company->trial_ends_at->format('M d, Y g:i A') }}
                                                @if($company->isOnTrial())
                                                    <span class="text-yellow-600">({{ $company->trial_ends_at->diffForHumans() }})</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('company.profile') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-400 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Update Company Profile') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sync color picker with text input
        document.getElementById('primary_color').addEventListener('input', function() {
            document.querySelector('input[name="primary_color_text"]').value = this.value;
        });

        document.getElementById('secondary_color').addEventListener('input', function() {
            document.querySelector('input[name="secondary_color_text"]').value = this.value;
        });

        // Sync text input with color picker
        document.querySelector('input[name="primary_color_text"]').addEventListener('input', function() {
            document.getElementById('primary_color').value = this.value;
        });

        document.querySelector('input[name="secondary_color_text"]').addEventListener('input', function() {
            document.getElementById('secondary_color').value = this.value;
        });
    </script>
</x-app-layout>
