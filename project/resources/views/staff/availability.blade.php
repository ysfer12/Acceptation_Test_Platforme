<!-- resources/views/staff/availability.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Availability') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-6 shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-6 shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Add New Availability -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Add New Availability</h3>

                    <form action="{{ route('staff.availability.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Availability Type</label>
                            <div class="flex space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="availability_type" value="recurring" class="form-radio text-blue-600" checked>
                                    <span class="ml-2">Weekly Recurring</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="availability_type" value="one-time" class="form-radio text-blue-600">
                                    <span class="ml-2">One-time</span>
                                </label>
                            </div>
                        </div>

                        <div id="recurring-fields" class="mb-4">
                            <label for="day_of_week" class="block text-sm font-medium text-gray-700 mb-1">Day of Week</label>
                            <select id="day_of_week" name="day_of_week" class="form-select rounded-md shadow-sm mt-1 block w-full">
                                <option value="0">Sunday</option>
                                <option value="1">Monday</option>
                                <option value="2">Tuesday</option>
                                <option value="3">Wednesday</option>
                                <option value="4">Thursday</option>
                                <option value="5">Friday</option>
                                <option value="6">Saturday</option>
                            </select>
                        </div>

                        <div id="one-time-fields" class="mb-4 hidden">
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                            <input type="date" id="date" name="date" class="form-input rounded-md shadow-sm mt-1 block w-full" min="{{ date('Y-m-d') }}">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                                <input type="time" id="start_time" name="start_time" class="form-input rounded-md shadow-sm mt-1 block w-full">
                            </div>
                            <div>
                                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                                <input type="time" id="end_time" name="end_time" class="form-input rounded-md shadow-sm mt-1 block w-full">
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Add Availability
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Recurring Availabilities -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Weekly Recurring Availabilities</h3>

                    @if($recurringAvailabilities->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Day</th>
                                    <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Start Time</th>
                                    <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">End Time</th>
                                    <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                @foreach($recurringAvailabilities as $availability)
                                    <tr>
                                        <td class="py-3 px-4">
                                            @php
                                                $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                            @endphp
                                            {{ $days[$availability->day_of_week] ?? 'Unknown' }}
                                        </td>
                                        <td class="py-3 px-4">{{ Carbon\Carbon::parse($availability->start_time)->format('g:i A') }}</td>
                                        <td class="py-3 px-4">{{ Carbon\Carbon::parse($availability->end_time)->format('g:i A') }}</td>
                                        <td class="py-3 px-4">
                                            <form action="{{ route('staff.availability.destroy', $availability) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to remove this availability?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-lg p-6 text-center text-gray-500">
                            No recurring availabilities set up yet.
                        </div>
                    @endif
                </div>
            </div>

            <!-- One-time Availabilities -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">One-time Availabilities</h3>

                    @if($oneTimeAvailabilities->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                                    <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Start Time</th>
                                    <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">End Time</th>
                                    <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                @foreach($oneTimeAvailabilities as $availability)
                                    <tr>
                                        <td class="py-3 px-4">{{ $availability->date->format('M d, Y') }}</td>
                                        <td class="py-3 px-4">{{ Carbon\Carbon::parse($availability->start_time)->format('g:i A') }}</td>
                                        <td class="py-3 px-4">{{ Carbon\Carbon::parse($availability->end_time)->format('g:i A') }}</td>
                                        <td class="py-3 px-4">
                                            <form action="{{ route('staff.availability.destroy', $availability) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to remove this availability?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-lg p-6 text-center text-gray-500">
                            No one-time availabilities set up yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const availabilityType = document.querySelectorAll('input[name="availability_type"]');
            const recurringFields = document.getElementById('recurring-fields');
            const oneTimeFields = document.getElementById('one-time-fields');

            function toggleFields() {
                const selectedType = document.querySelector('input[name="availability_type"]:checked').value;
                
                if (selectedType === 'recurring') {
                    recurringFields.classList.remove('hidden');
                    oneTimeFields.classList.add('hidden');
                } else {
                    recurringFields.classList.add('hidden');
                    oneTimeFields.classList.remove('hidden');
                }
            }

            availabilityType.forEach(radio => {
                radio.addEventListener('change', toggleFields);
            });
        });
    </script>
</x-app-layout>