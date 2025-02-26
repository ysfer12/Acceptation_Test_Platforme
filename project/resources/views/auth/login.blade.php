<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-indigo-100 to-purple-200 flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-4xl bg-white shadow-2xl rounded-xl overflow-hidden grid grid-cols-1 md:grid-cols-2">
            <!-- Left Column - Image -->
            <div class="hidden md:block relative">
                <img
                    src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1172&q=80"
                    alt="Technology Background"
                    class="absolute inset-0 w-full h-full object-cover"
                />
                <div class="absolute inset-0 bg-indigo-600 opacity-60"></div>
                <div class="absolute inset-0 flex flex-col justify-center items-center text-white p-8 text-center">
                    <h2 class="text-4xl font-bold mb-4">Hello, Friend!</h2>
                    <p class="text-xl mb-6">Enter your personal details and start your journey with us</p>
                </div>
            </div>

            <!-- Right Column - Login Form -->
            <div class="p-8 flex flex-col justify-center">
                <div class="text-center mb-8">
                    <x-application-logo class="mx-auto h-20 w-20 text-indigo-600 mb-4" />
                    <h2 class="text-3xl font-extrabold text-gray-900">
                        {{ __('Welcome Back') }}
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">
                        {{ __('Sign in to continue to your account') }}
                    </p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Validation Errors -->
                <x-auth-validation-errors class="mb-4" :errors="$errors" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-label for="email" :value="__('Email Address')"
                                 class="block text-sm font-medium text-gray-700 mb-1" />

                        <x-input
                            id="email"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                            autofocus
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400
                                   focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm
                                   transition duration-300 ease-in-out hover:border-indigo-400"
                        />
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex justify-between items-center">
                            <x-label for="password" :value="__('Password')"
                                     class="block text-sm font-medium text-gray-700 mb-1" />

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                   class="text-sm text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition duration-300">
                                    {{ __('Forgot password?') }}
                                </a>
                            @endif
                        </div>

                        <x-input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400
                                   focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm
                                   transition duration-300 ease-in-out hover:border-indigo-400"
                        />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input
                            id="remember_me"
                            type="checkbox"
                            name="remember"
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded
                                   transition duration-300 ease-in-out"
                        >
                        <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                            {{ __('Remember me') }}
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <x-button class="w-full flex justify-center py-2 px-4
                                         bg-indigo-600 text-white font-bold
                                         rounded-md hover:bg-indigo-700
                                         focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                                         transition duration-300 ease-in-out transform hover:scale-105">
                            {{ __('Sign In') }}
                        </x-button>
                    </div>

                    <!-- Registration Link -->
                    <div class="text-center">
                        <p class="mt-2 text-sm text-gray-600">
                            {{ __('Don\'t have an account?') }}
                            <a href="{{ route('register') }}"
                               class="font-medium text-indigo-600 hover:text-indigo-500
                                      transition duration-300">
                                {{ __('Sign up') }}
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
