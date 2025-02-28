<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-6 shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Statistiques générales -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <span class="text-3xl font-bold text-gray-800">{{ $stats['totalCandidates'] ?? 0 }}</span>
                        <p class="text-sm text-gray-600 mt-2">Candidats totaux</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <span class="text-3xl font-bold text-yellow-500">{{ $stats['pendingProfiles'] ?? 0 }}</span>
                        <p class="text-sm text-gray-600 mt-2">Profils incomplets</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <span class="text-3xl font-bold text-blue-500">{{ $stats['completedQuizzes'] ?? 0 }}</span>
                        <p class="text-sm text-gray-600 mt-2">Quiz terminés</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <span class="text-3xl font-bold text-green-500">{{ $stats['passedQuizzes'] ?? 0 }}</span>
                        <p class="text-sm text-gray-600 mt-2">Quiz réussis</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <span class="text-3xl font-bold text-purple-500">{{ $stats['pendingAppointments'] ?? 0 }}</span>
                        <p class="text-sm text-gray-600 mt-2">Rendez-vous à venir</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Candidats récents -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Candidats récents</h3>
                            <a href="{{ route('admin.candidates') }}" class="text-sm text-blue-600 hover:text-blue-800">Voir tous</a>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nom</th>
                                    <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                                    <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Statut</th>
                                    <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                @forelse($recentCandidates ?? collect([]) as $candidate)
                                    <tr>
                                        <td class="py-3 px-4">
                                            <a href="{{ route('admin.candidate.details', $candidate) }}" class="text-blue-600 hover:text-blue-900">
                                                {{ $candidate->first_name ?? '' }} {{ $candidate->last_name ?? $candidate->name }}
                                            </a>
                                        </td>
                                        <td class="py-3 px-4 text-sm">{{ $candidate->email }}</td>
                                        <td class="py-3 px-4">
                                            @if($candidate->email_verified_at)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Vérifié</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">En attente</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-sm">{{ $candidate->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-3 px-4 text-center text-gray-500">Aucun candidat récent</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Derniers résultats de quiz -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Derniers résultats de quiz</h3>
                            <a href="{{ route('admin.quizzes') }}" class="text-sm text-blue-600 hover:text-blue-800">Gérer les quiz</a>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Candidat</th>
                                    <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Score</th>
                                    <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Statut</th>
                                    <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                @forelse($recentQuizResults ?? collect([]) as $result)
                                    <tr>
                                        <td class="py-3 px-4">
                                            <a href="{{ route('admin.candidate.details', $result->user) }}" class="text-blue-600 hover:text-blue-900">
                                                {{ $result->user->first_name ?? '' }} {{ $result->user->last_name ?? $result->user->name }}
                                            </a>
                                        </td>
                                        <td class="py-3 px-4 text-sm">{{ $result->score }}%</td>
                                        <td class="py-3 px-4">
                                            @if($result->passed)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Réussi</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Échoué</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-sm">{{ $result->completed_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-3 px-4 text-center text-gray-500">Aucun résultat récent</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rendez-vous à venir -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Rendez-vous à venir</h3>
                        <a href="{{ route('admin.appointments') }}" class="text-sm text-blue-600 hover:text-blue-800">Gérer tous les rendez-vous</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100">
                            <tr>
                                <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Candidat</th>
                                <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date & Heure</th>
                                <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Lieu</th>
                                <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Examinateur</th>
                                <th class="py-2 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                            @forelse($upcomingAppointments ?? collect([]) as $appointment)
                                <tr>
                                    <td class="py-3 px-4">
                                        <a href="{{ route('admin.candidate.details', $appointment->user) }}" class="text-blue-600 hover:text-blue-900">
                                            {{ $appointment->user->first_name ?? '' }} {{ $appointment->user->last_name ?? $appointment->user->name }}
                                        </a>
                                    </td>
                                    <td class="py-3 px-4 text-sm">{{ $appointment->scheduled_at->format('d/m/Y H:i') }}</td>
                                    <td class="py-3 px-4 text-sm">{{ $appointment->location }}</td>
                                    <td class="py-3 px-4 text-sm">{{ $appointment->staff->name ?? 'Non assigné' }}</td>
                                    <td class="py-3 px-4 text-sm">
                                        <button type="button"
                                                class="text-blue-600 hover:text-blue-900"
                                                onclick="openEditModal({{ $appointment->id }})">
                                            Modifier
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-3 px-4 text-center text-gray-500">Aucun rendez-vous à venir</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Actions Rapides</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('admin.candidates') }}" class="p-4 bg-blue-50 rounded-lg border border-blue-100 hover:bg-blue-100 transition">
                            <div class="flex items-center">
                                <div class="bg-blue-500 rounded-lg p-3 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">Gérer les Candidats</h4>
                                    <p class="text-sm text-gray-600">Voir et gérer les informations des candidats</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('admin.appointments') }}" class="p-4 bg-green-50 rounded-lg border border-green-100 hover:bg-green-100 transition">
                            <div class="flex items-center">
                                <div class="bg-green-500 rounded-lg p-3 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">Planifier des Rendez-vous</h4>
                                    <p class="text-sm text-gray-600">Créer et gérer les évaluations en personne</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('admin.quizzes') }}" class="p-4 bg-purple-50 rounded-lg border border-purple-100 hover:bg-purple-100 transition">
                            <div class="flex items-center">
                                <div class="bg-purple-500 rounded-lg p-3 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">Gérer les Quiz</h4>
                                    <p class="text-sm text-gray-600">Configurer les évaluations en ligne</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Appointment Modal -->
    <div id="editModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
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
                            @foreach(App\Models\User::where('role', 'staff')->get() as $staff)
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

    <script>
        // Appointment edit modal functionality
        document.addEventListener('DOMContentLoaded', function() {
            const editModal = document.getElementById('editModal');
            const closeModalBtn = document.getElementById('close-modal');
            const cancelBtn = document.getElementById('cancel-button');
            const overlay = document.getElementById('modal-overlay');
            const editForm = document.getElementById('editAppointmentForm');

            window.openEditModal = function(appointmentId) {
                // Set the form action URL with the appointment ID
                editForm.action = `/admin/appointments/${appointmentId}`;

                // In a real implementation, you would fetch the appointment data and populate the form
                // For example:
                // fetch(`/admin/appointments/${appointmentId}/data`)
                //     .then(response => response.json())
                //     .then(data => {
                //         document.getElementById('edit_staff_id').value = data.staff_id;
                //         document.getElementById('edit_scheduled_at').value = data.scheduled_at;
                //         document.getElementById('edit_location').value = data.location;
                //         document.getElementById('edit_status').value = data.status;
                //         document.getElementById('edit_notes').value = data.notes;
                //     });

                // Show the modal
                editModal.classList.remove('hidden');
            };

            function closeModal() {
                editModal.classList.add('hidden');
            }

            closeModalBtn.addEventListener('click', closeModal);
            cancelBtn.addEventListener('click', closeModal);
            overlay.addEventListener('click', closeModal);
        });
    </script>
</x-app-layout>
