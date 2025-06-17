<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Company Setup') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Welcome to FinMan!</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Let's set up your company profile to get started with your financial management.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('setup.company.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Company Name -->
                        <div class="mb-6">
                            <x-input-label for="name" :value="__('Company Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div class="mb-6">
                            <x-input-label for="email" :value="__('Company Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-500">Optional - Used for company communications</p>
                        </div>

                        <!-- Phone -->
                        <div class="mb-6">
                            <x-input-label for="phone" :value="__('Phone Number')" />
                            <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <!-- Website -->
                        <div class="mb-6">
                            <x-input-label for="website" :value="__('Website')" />
                            <x-text-input id="website" class="block mt-1 w-full" type="url" name="website" :value="old('website')" placeholder="https://example.com" />
                            <x-input-error :messages="$errors->get('website')" class="mt-2" />
                        </div>

                        <!-- About -->
                        <div class="mb-6">
                            <x-input-label for="about" :value="__('About Company')" />
                            <textarea id="about" name="about" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Brief description of your company...">{{ old('about') }}</textarea>
                            <x-input-error :messages="$errors->get('about')" class="mt-2" />
                        </div>

                        <!-- Logo Upload -->
                        <div class="mb-6">
                            <x-input-label for="logo" :value="__('Company Logo')" />
                            <input id="logo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" type="file" name="logo" accept="image/*" />
                            <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-500">Optional - Upload your company logo (JPG, PNG, GIF - Max 2MB)</p>
                        </div>

                        <!-- Trial Information -->
                        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h4 class="text-sm font-medium text-blue-900">30-Day Free Trial</h4>
                                    <p class="text-sm text-blue-700">Your company will start with a 30-day free trial. No credit card required!</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <x-primary-button class="ml-4">
                                {{ __('Create Company & Get Started') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
