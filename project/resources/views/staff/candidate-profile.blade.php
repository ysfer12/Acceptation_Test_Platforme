<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Candidate Profile') }}
            </h2>
            <a href="{{ route('staff.dashboard') }}" class="px-4 py-2 bg-gray-300 rounded-md text-gray-800 text-sm font-medium hover:bg-gray-400">
                Back to Dashboard
            </a>
        </div>
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

            <!-- Personal Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Personal Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500">Full Name</span>
                                <span class="block mt-1">{{ $user->first_name ?? '' }} {{ $user->last_name ?? $user->name }}</span>
                            </div>

                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500">Email</span>
                                <span class="block mt-1">{{ $user->email }}</span>
                                @if($user->email_verified_at)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 mt-1">Verified on {{ $user->email_verified_at->format('d/m/Y') }}</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 mt-1">Not verified</span>
                                @endif
                            </div>

                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500">Date of Birth</span>
                                <span class="block mt-1">{{ $user->birth_date ? $user->birth_date->format('d/m/Y') : 'Not provided' }}</span>
                            </div>
                        </div>

                        <div>
                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500">Phone</span>
                                <span class="block mt-1">{{ $user->phone ?? 'Not provided' }}</span>
                            </div>

                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500">Address</span>
                                <span class="block mt-1">{{ $user->address ?? 'Not provided' }}</span>
                            </div>

                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500">ID Card</span>
                                @if($user->id_card_path)
                                    <a href="{{ Storage::url($user->id_card_path) }}" target="_blank" class="inline-flex items-center mt-1 px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-md">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View Document
                                    </a>
                                @else
                                    <span class="block mt-1 text-gray-500">Not provided</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assessment Status -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Quiz Results -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Online Assessment</h3>

                        @if($quizResults->count() > 0)
                            <div class="space-y-4">
                                @foreach($quizResults as $result)
                                    <div class="bg-{{ $result->passed ? 'green' : 'yellow' }}-50 rounded-lg p-4 border border-{{ $result->passed ? 'green' : 'yellow' }}-100">
                                        <div class="flex justify-between">
                                            <div>
                                                <p class="font-medium">{{ $result->quiz->title ?? 'Assessment' }}</p>
                                                <p class="text-sm text-gray-600">Completed: {{ $result->completed_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                            <div class="text-{{ $result->passed ? 'green' : 'yellow' }}-700 font-bold text-xl">
                                                {{ $result->score }}%
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $result->passed ? 'green' : 'yellow' }}-100 text-{{ $result->passed ? 'green' : 'yellow' }}-800">
                                                {{ $result->passed ? 'Passed' : 'Failed' }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-lg p-6 text-center">
                                <p class="text-gray-500">Candidate has not completed any online assessments yet.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Evaluations -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold border-b pb-2">Evaluations</h3>
                            <div>
                                @if(isset($appointment) && $appointment && $appointment->status === 'scheduled' && $appointment->staff_id === auth()->id())
                                    <a href="{{ route('staff.evaluation.create', [$user->id, $appointment->id]) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                        Create Evaluation for Appointment
                                    </a>
                                @else
                                    <a href="{{ route('staff.evaluation.create', $user->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                        Create New Evaluation
                                    </a>
                                @endif
                            </div>
                        </div>

                        @if($evaluations->count() > 0)
                            <div class="space-y-4">
                                @foreach($evaluations as $evaluation)
                                    <div class="border rounded-lg overflow-hidden">
                                        <div class="bg-gray-50 px-4 py-3 flex justify-between items-center">
                                            <div>
                                                <span class="font-medium">{{ ucfirst($evaluation->type) }} Assessment</span>
                                                <span class="text-sm text-gray-500 ml-2">{{ $evaluation->created_at->format('M d, Y') }}</span>
                                            </div>
                                            <span class="px-3 py-1 rounded-full text-xs font-medium 
                                                {{ $evaluation->result === 'pass' ? 'bg-green-100 text-green-800' : 
                                                   ($evaluation->result === 'fail' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ ucfirst($evaluation->result) }}
                                            </span>
                                        </div>
                                        
                                        <div class="p-4">
                                            @if($evaluation->score)
                                                <div class="mb-3">
                                                    <span class="font-medium">Overall Score:</span> 
                                                    <span class="ml-1 px-2 py-1 bg-{{ $evaluation->score >= 70 ? 'green' : 'yellow' }}-100 rounded text-{{ $evaluation->score >= 70 ? 'green' : 'yellow' }}-800">
                                                        {{ $evaluation->score }}/100
                                                    </span>
                                                </div>
                                            @endif
                                            
                                            @if($evaluation->criteria_scores && count($evaluation->criteria_scores) > 0)
                                                <div class="mb-3">
                                                    <div class="font-medium mb-2">Detailed Assessment:</div>
                                                    <div class="grid grid-cols-2 gap-2">
                                                        @foreach($evaluation->criteria_scores as $criterion => $score)
                                                            <div class="flex justify-between items-center">
                                                                <span class="text-sm">{{ ucfirst(str_replace('_', ' ', $criterion)) }}:</span>
                                                                <span class="ml-2 px-2 py-0.5 bg-gray-100 rounded text-gray-800 text-sm">{{ $score }}/5</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            @if($evaluation->strengths)
                                                <div class="mb-3">
                                                    <div class="font-medium">Strengths:</div>
                                                    <p class="text-sm">{{ $evaluation->strengths }}</p>
                                                </div>
                                            @endif
                                            
                                            @if($evaluation->weaknesses)
                                                <div class="mb-3">
                                                    <div class="font-medium">Areas for Improvement:</div>
                                                    <p class="text-sm">{{ $evaluation->weaknesses }}</p>
                                                </div>
                                            @endif
                                            
                                            @if($evaluation->comments)
                                                <div>
                                                    <div class="font-medium">Additional Comments:</div>
                                                    <p class="text-sm">{{ $evaluation->comments }}</p>
                                                </div>
                                            @endif
                                            
                                            <div class="mt-3 text-xs text-gray-500">
                                                Evaluated by: {{ $evaluation->staff->name }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-4 bg-gray-50 rounded text-center text-gray-500">
                                No evaluations have been recorded yet.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Appointments -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Appointments</h3>

                    @if($appointments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date & Time</th>
                                    <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Location</th>
                                    <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                    <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Notes</th>
                                    <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                @foreach($appointments as $appointment)
                                    <tr>
                                        <td class="py-3 px-4 text-sm">{{ $appointment->scheduled_at->format('d/m/Y H:i') }}</td>
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
                                            <div class="max-w-xs truncate">
                                                {{ $appointment->notes ?: 'No notes' }}
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-sm">
                                            <div class="flex space-x-2">
                                                <button type="button" class="text-blue-600 hover:text-blue-900" onclick="openNotesModal({{ $appointment->id }})">
                                                    Edit Notes
                                                </button>
                                                @if($appointment->status === 'scheduled' && $appointment->staff_id === auth()->id())
                                                    <form action="{{ route('staff.appointment.complete', $appointment) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-green-600 hover:text-green-900">Complete</button>
                                                    </form>
                                                    <a href="{{ route('staff.evaluation.create', [$user->id, $appointment->id]) }}" class="text-purple-600 hover:text-purple-900">
                                                        Evaluate
                                                    </a>
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
                            <p class="text-gray-500">No appointments have been scheduled with this candidate.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Actions</h3>

                    <div class="flex space-x-4">
                        <a href="{{ route('staff.evaluation.create', $user->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                            Create New Evaluation
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