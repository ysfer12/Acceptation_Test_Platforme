<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Staff Dashboard') }}
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

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <span class="text-3xl font-bold text-blue-600">{{ $appointmentStats['today'] }}</span>
                        <p class="text-sm text-gray-600 mt-2">Today's Appointments</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <span class="text-3xl font-bold text-purple-600">{{ $appointmentStats['pending'] }}</span>
                        <p class="text-sm text-gray-600 mt-2">Upcoming Appointments</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <span class="text-3xl font-bold text-green-600">{{ $appointmentStats['completed'] }}</span>
                        <p class="text-sm text-gray-600 mt-2">Completed Appointments</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="relative pt-1">
                            <div class="flex mb-2 items-center justify-between">
                                <div>
                                    <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-blue-600 bg-blue-200">
                                        Profile Completion
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-semibold inline-block text-blue-600">
                                        {{ $profileCompletion }}%
                                    </span>
                                </div>
                            </div>
                            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-blue-200">
                                <div style="width:{{ $profileCompletion }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Today's Appointments -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Today's Appointments</h3>

                        @if($todayAppointments->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white">
                                    <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Time</th>
                                        <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Candidate</th>
                                        <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Location</th>
                                        <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                        <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                    @foreach($todayAppointments as $appointment)
                                        <tr>
                                            <td class="py-3 px-4 text-sm">{{ $appointment->scheduled_at->format('H:i') }}</td>
                                            <td class="py-3 px-4">
                                                <a href="{{ route('staff.candidate.profile', $appointment->user) }}" class="text-blue-600 hover:text-blue-900">
                                                    {{ $appointment->user->first_name ?? '' }} {{ $appointment->user->last_name ?? $appointment->user->name }}
                                                </a>
                                            </td>
                                            <td class="py-3 px-4 text-sm">{{ $appointment->location }}</td>
                                            <td class="py-3 px-4">
                                                @if($appointment->status === 'scheduled')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Scheduled</span>
                                                @elseif($appointment->status === 'completed')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Completed</span>
                                                @elseif($appointment->status === 'cancelled')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Cancelled</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4 text-sm">
                                                <div class="flex space-x-2">
                                                    <button type="button" class="text-blue-600 hover:text-blue-900" onclick="openNotesModal({{ $appointment->id }})">
                                                        Notes
                                                    </button>
                                                    @if($appointment->status === 'scheduled')
                                                        <form action="{{ route('staff.appointment.complete', $appointment) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" class="text-green-600 hover:text-green-900">Complete</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-lg p-6 text-center">
                                <p class="text-gray-500">No appointments scheduled for today.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Upcoming Appointments -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold border-b pb-2">Upcoming Appointments</h3>
                            <a href="{{ route('staff.calendar') }}" class="text-sm text-blue-600 hover:text-blue-800">View Calendar</a>
                        </div>

                        @if($upcomingAppointments->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white">
                                    <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date & Time</th>
                                        <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Candidate</th>
                                        <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Location</th>
                                    </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                    @foreach($upcomingAppointments as $appointment)
                                        <tr>
                                            <td class="py-3 px-4 text-sm">{{ $appointment->scheduled_at->format('d/m/Y H:i') }}</td>
                                            <td class="py-3 px-4">
                                                <a href="{{ route('staff.candidate.profile', $appointment->user) }}" class="text-blue-600 hover:text-blue-900">
                                                    {{ $appointment->user->first_name ?? '' }} {{ $appointment->user->last_name ?? $appointment->user->name }}
                                                </a>
                                            </td>
                                            <td class="py-3 px-4 text-sm">{{ $appointment->location }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-lg p-6 text-center">
                                <p class="text-gray-500">No upcoming appointments scheduled.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Evaluations -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold border-b pb-2">Recent Evaluations</h3>
                        <a href="{{ route('staff.evaluation.form') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                            New Evaluation
                        </a>
                    </div>

                    @if($recentEvaluations->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                                    <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Candidate</th>
                                    <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                                    <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Result</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                @foreach($recentEvaluations as $evaluation)
                                    <tr>
                                        <td class="py-3 px-4 text-sm">{{ $evaluation->created_at->format('d/m/Y') }}</td>
                                        <td class="py-3 px-4">
                                            <a href="{{ route('staff.candidate.profile', $evaluation->user) }}" class="text-blue-600 hover:text-blue-900">
                                                {{ $evaluation->user->first_name ?? '' }} {{ $evaluation->user->last_name ?? $evaluation->user->name }}
                                            </a>
                                        </td>
                                        <td class="py-3 px-4 text-sm">
                                            @if($evaluation->type === 'interview')
                                                Interview
                                            @elseif($evaluation->type === 'technical')
                                                Technical
                                            @elseif($evaluation->type === 'soft_skills')
                                                Soft Skills
                                            @endif
                                        </td>
                                        <td class="py-3 px-4">
                                            @if($evaluation->result === 'pass')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Pass</span>
                                            @elseif($evaluation->result === 'fail')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Fail</span>
                                            @elseif($evaluation->result === 'pending')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-lg p-6 text-center">
                            <p class="text-gray-500">No evaluations recorded yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Links -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Quick Links</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('staff.calendar') }}" class="p-4 bg-blue-50 rounded-lg border border-blue-100 hover:bg-blue-100 transition">
                            <div class="flex items-center">
                                <div class="bg-blue-500 rounded-lg p-3 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">Appointment Calendar</h4>
                                    <p class="text-sm text-gray-600">View all your scheduled appointments</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('staff.evaluation.form') }}" class="p-4 bg-green-50 rounded-lg border border-green-100 hover:bg-green-100 transition">
                            <div class="flex items-center">
                                <div class="bg-green-500 rounded-lg p-3 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">Evaluate Candidate</h4>
                                    <p class="text-sm text-gray-600">Create a new candidate evaluation</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('profile.edit') }}" class="p-4 bg-purple-50 rounded-lg border border-purple-100 hover:bg-purple-100 transition">
                            <div class="flex items-center">
                                <div class="bg-purple-500 rounded-lg p-3 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">Update Profile</h4>
                                    <p class="text-sm text-gray-600">Manage your personal information</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes Modal -->
    <div id="notesModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="fixed inset-0 bg-black opacity-50" id="modal-overlay"></div>
        <div class="bg-white rounded-lg shadow-xl overflow-hidden w-full max-w-md z-10 relative">
            <div class="flex justify-between items-center p-6 bg-gray-50 border-b">
                <h3 class="text-lg font-medium text-gray-900">Appointment Notes</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500" id="close-modal">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="notesForm" action="" method="POST" class="p-6">
                @csrf
                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea id="notes" name="notes" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600" id="cancel-button">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Save Notes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notesModal = document.getElementById('notesModal');
            const closeModalBtn = document.getElementById('close-modal');
            const cancelBtn = document.getElementById('cancel-button');
            const overlay = document.getElementById('modal-overlay');
            const notesForm = document.getElementById('notesForm');
            const notesTextarea = document.getElementById('notes');

            window.openNotesModal = function(appointmentId) {
                // Set the form action URL with the appointment ID
                notesForm.action = `/staff/appointments/${appointmentId}/notes`;

                // Fetch the existing notes for this appointment
                fetch(`/staff/appointments/${appointmentId}/notes`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.notes) {
                            notesTextarea.value = data.notes;
                        } else {
                            notesTextarea.value = '';
                        }
                        notesModal.classList.remove('hidden');
                    })
                    .catch(error => {
                        console.error('Error fetching notes:', error);
                    });
            };

            function closeModal() {
                notesModal.classList.add('hidden');
            }

            closeModalBtn.addEventListener('click', closeModal);
            cancelBtn.addEventListener('click', closeModal);
            overlay.addEventListener('click', closeModal);
        });
    </script>
</x-app-layout>