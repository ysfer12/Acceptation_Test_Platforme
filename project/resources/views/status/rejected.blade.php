<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Account Rejected') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                <p class="font-bold">{{ __('Account Rejected') }}</p>
                <p>{{ __('Your account has been rejected. Please contact the administrator for more information.') }}</p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <h3 class="text-xl font-medium mb-4">Contact Information</h3>
                    <p class="mb-4">If you believe this is an error or would like more information about why your account was rejected, please contact our support team:</p>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p><strong>Email:</strong> support@example.com</p>
                        <p><strong>Phone:</strong> (123) 456-7890</p>
                        <p><strong>Hours:</strong> Monday-Friday, 9am-5pm</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
