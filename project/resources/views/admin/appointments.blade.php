<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion des Rendez-vous') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-6 shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Planifier un rendez-vous -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Planifier un Nouveau Rendez-vous</h3>

                    <form action="{{ route('admin.appointments.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Candidat</label>
                                <select id="user_id" name="user_id" class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 w-full">
                                    <option value="">Sélectionnez un candidat</option>
                                    @foreach(App\Models\User::where('role', 'candidate')->get() as $candidate)
                                        <option value="{{ $candidate->id }}">{{ $candidate->first_name ?? '' }} {{ $candidate->last_name ?? $candidate->name }} ({{ $candidate->email }})</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="staff_id" class="block text-sm font-medium text-gray-700 mb-1">Examinateur</label>
                                <select id="staff_id" name="staff_id" class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 w-full">
                                    <option value="">Sélectionnez un examinateur</option>
                                    @foreach($staffMembers as $staff)
                                        <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                                    @endforeach
                                </select>
                                @error('staff_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="scheduled_at" class="block text-sm font-medium text-gray-700 mb-1">Date et Heure</label>
                                <input type="datetime-local" id="scheduled_at" name="scheduled_at" class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 w-full">
                                @error('scheduled_at')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Lieu</label>
                                <input type="text" id="location" name="location" class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 w-full">
                                @error('location')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea id="notes" name="notes" rows="3" class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 w-full"></textarea>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                                Planifier le rendez-vous
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Liste des Rendez-vous -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Liste des Rendez-vous</h3>
                        <div class="flex space-x-2">
                            <select id="status-filter" class="border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Tous les statuts</option>
                                <option value="scheduled">Planifiés</option>
                                <option value="completed">Terminés</option>
                                <option value="cancelled">Annulés</option>
                            </select>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100">
                            <tr>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Candidat</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date & Heure</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Lieu</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Examinateur</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Statut</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                            @forelse($appointments as $appointment)
                                <tr>
                                    <td class="py-3 px-4">
                                        <a href="{{ route('admin.candidate.details', $appointment->user) }}" class="text-blue-600 hover:text-blue-900">
                                            {{ $appointment->user->first_name ?? '' }} {{ $appointment->user->last_name ?? $appointment->user->name }}
                                        </a>
                                    </td>
                                    <td class="py-3 px-4 text-sm">{{ $appointment->scheduled_at->format('d/m/Y H:i') }}</td>
                                    <td class="py-3 px-4 text-sm">{{ $appointment->location }}</td>
                                    <td class="py-3 px-4 text-sm">{{ $appointment->staff->name ?? 'Non assigné' }}</td>
                                    <td class="py-3 px-4">
                                        @if($appointment->status === 'scheduled')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Planifié</span>
                                        @elseif($appointment->status === 'completed')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Terminé</span>
                                        @elseif($appointment->status === 'cancelled')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Annulé</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        <button type="button"
                                                class="text-blue-600 hover:text-blue-900 mr-2"
                                                onclick="openEditModal({{ $appointment->id }})">
                                            Modifier
                                        </button>
                                        @if($appointment->status !== 'cancelled')
                                            <form action="{{ route('admin.appointments.update', $appointment) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="staff_id" value="{{ $appointment->staff_id }}">
                                                <input type="hidden" name="scheduled_at" value="{{ $appointment->scheduled_at->format('Y-m-d\TH:i') }}">
                                                <input type="hidden" name="location" value="{{ $appointment->location }}">
                                                <input type="hidden" name="notes" value="{{ $appointment->notes }}">
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-900"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous?')">
                                                    Annuler
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-3 px-4 text-center text-gray-500">Aucun rendez-vous trouvé</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $appointments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Appointment Modal -->
    <div id="editAppointmentModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="fixed inset-0 bg-black opacity-50" id="modal-overlay"></div>
        <div class="bg-white rounded-lg shadow-xl overflow-hidden w-full max-w-2xl z-10 relative">
            <div class="flex justify-between items-center p-6 bg-gray-50 border-b">
                <h3 class="text-lg font-medium text-gray-900">Modifier le rendez-vous</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500" id="close-modal">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="editAppointmentForm" action="" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="edit_staff_id" class="block text-sm font-medium text-gray-700">Examinateur</label>
                        <select id="edit_staff_id" name="staff_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                            <option value="">Sélectionnez un examinateur</option>
                            @foreach($staffMembers as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="edit_scheduled_at" class="block text-sm font-medium text-gray-700">Date et Heure</label>
                        <input type="datetime-local" name="scheduled_at" id="edit_scheduled_at" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                    </div>

                    <div class="mb-4">
                        <label for="edit_location" class="block text-sm font-medium text-gray-700">Lieu</label>
                        <input type="text" name="location" id="edit_location" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                    </div>

                    <div class="mb-4">
                        <label for="edit_status" class="block text-sm font-medium text-gray-700">Statut</label>
                        <select id="edit_status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                            <option value="scheduled">Planifié</option>
                            <option value="completed">Terminé</option>
                            <option value="cancelled">Annulé</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="edit_notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea id="edit_notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
                </div>

                <div class="mt-5 sm:mt-6 flex justify-end space-x-2">
                    <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600" id="cancel-button">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="flex justify-end mb-4">
    <form action="{{ route('admin.auto.assign.candidates') }}" method="POST" class="inline">
        @csrf
        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
            Auto-Assign Candidates
        </button>
    </form>
</div>
    <script>
        // Appointment edit modal functionality
        document.addEventListener('DOMContentLoaded', function() {
            const editModal = document.getElementById('editAppointmentModal');
            const closeModalBtn = document.getElementById('close-modal');
            const cancelBtn = document.getElementById('cancel-button');
            const overlay = document.getElementById('modal-overlay');
            const editForm = document.getElementById('editAppointmentForm');

            window.openEditModal = function(appointmentId) {
                // Set the form action URL with the appointment ID
                editForm.action = `/admin/appointments/${appointmentId}`;

                // Fetch appointment data and populate the form (example)
                // In a real implementation, you might want to fetch the data via AJAX
                // or have it embedded in data attributes

                // For now, let's assume we're setting some default values
                // In a real app, you'd populate these with the actual appointment data
                document.getElementById('edit_staff_id').value = ''; // Set the staff ID
                document.getElementById('edit_scheduled_at').value = ''; // Set the date
                document.getElementById('edit_location').value = ''; // Set the location
                document.getElementById('edit_status').value = 'scheduled'; // Set the status
                document.getElementById('edit_notes').value = ''; // Set the notes

                editModal.classList.remove('hidden');
            };

            function closeModal() {
                editModal.classList.add('hidden');
            }

            closeModalBtn.addEventListener('click', closeModal);
            cancelBtn.addEventListener('click', closeModal);
            overlay.addEventListener('click', closeModal);

            // Status filter functionality
            const statusFilter = document.getElementById('status-filter');
            statusFilter.addEventListener('change', function() {
                const status = this.value;
                // Here you would implement the filtering logic
                // For a simple implementation without page refresh, you could:
                // 1. Hide all rows
                // 2. Show only rows matching the selected status
                // For a more robust solution, you'd want to submit the form or use AJAX
            });
        });
    </script>
</x-app-layout>
