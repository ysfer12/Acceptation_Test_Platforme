<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Account Pending') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
                <p class="font-bold">{{ __('Account Pending') }}</p>
                <p>{{ __('Your account is currently pending verification. Please check your email and click on the verification link.') }}</p>

                <form class="mt-4" method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-yellow-500 text-white font-semibold rounded hover:bg-yellow-600">
                        {{ __('Resend Verification Email') }}
                    </button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-medium mb-4">Email Verification Instructions</h3>
                    <p class="mb-4">To complete your registration and access the candidate portal, please follow these steps:</p>

                    <ol class="list-decimal pl-6 space-y-2 mb-6">
                        <li>Check your email inbox for a message from our system</li>
                        <li>Look for an email with the subject "Verify Email Address"</li>
                        <li>Click on the "Verify Email Address" button in the email</li>
                        <li>If you don't see the email, check your spam/junk folder</li>
                        <li>If you still can't find it, click the "Resend Verification Email" button above</li>
                    </ol>

                    <p class="text-sm text-gray-600">After verifying your email, you'll be able to complete your profile and begin the assessment process.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
