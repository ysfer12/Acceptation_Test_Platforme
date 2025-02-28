<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Détails du Candidat') }}
            </h2>
            <a href="{{ route('admin.candidates') }}" class="px-4 py-2 bg-gray-300 rounded-md text-gray-800 text-sm font-medium hover:bg-gray-400">
                Retour à la liste
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

            <!-- Informations du Candidat -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Informations Personnelles</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500">Nom Complet</span>
                                <span class="block mt-1">{{ $user->first_name ?? '' }} {{ $user->last_name ?? $user->name }}</span>
                            </div>

                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500">Email</span>
                                <span class="block mt-1">{{ $user->email }}</span>
                                @if($user->email_verified_at)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 mt-1">Vérifié le {{ $user->email_verified_at->format('d/m/Y H:i') }}</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 mt-1">Non vérifié</span>
                                @endif
                            </div>

                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500">Date de naissance</span>
                                <span class="block mt-1">{{ $user->birth_date ? $user->birth_date->format('d/m/Y') : 'Non renseigné' }}</span>
                            </div>
                        </div>

                        <div>
                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500">Téléphone</span>
                                <span class="block mt-1">{{ $user->phone ?? 'Non renseigné' }}</span>
                            </div>

                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500">Adresse</span>
                                <span class="block mt-1">{{ $user->address ?? 'Non renseigné' }}</span>
                            </div>

                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-500">Carte d'identité</span>
                                @if($user->id_card_path)
                                    <a href="{{ Storage::url($user->id_card_path) }}" target="_blank" class="inline-flex items-center mt-1 px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-md">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Voir le document
                                    </a>
                                @else
                                    <span class="block mt-1 text-gray-500">Non fournie</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Résultats de Quiz -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Résultats des Quiz</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100">
                            <tr>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Quiz</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Score</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Réussite</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                            @forelse($quizResults as $result)
                                <tr>
                                    <td class="py-3 px-4">{{ $result->quiz->title ?? 'Quiz' }}</td>
                                    <td class="py-3 px-4">{{ $result->score }}%</td>
                                    <td class="py-3 px-4">
                                        @if($result->passed)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Réussi</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Échoué</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-sm">{{ $result->completed_at->format('d/m/Y H:i') }}</td>
                                    <td class="py-3 px-4">
                                        <button type="button" class="text-blue-600 hover:text-blue-900" onclick="openQuizResultModal({{ $result->id }})">Voir détails</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-3 px-4 text-center text-gray-500">Aucun quiz terminé</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Rendez-vous -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold border-b pb-2">Rendez-vous</h3>
                        <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700" onclick="openAddAppointmentModal()">
                            Planifier un rendez-vous
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100">
                            <tr>
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
                                        <button type="button" class="text-blue-600 hover:text-blue-900 mr-2" onclick="openEditAppointmentModal({{ $appointment->id }})">Modifier</button>
                                        @if($appointment->status === 'scheduled')
                                            <form action="{{ route('admin.appointments.update', $appointment) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="staff_id" value="{{ $appointment->staff_id }}">
                                                <input type="hidden" name="scheduled_at" value="{{ $appointment->scheduled_at->format('Y-m-d\TH:i') }}">
                                                <input type="hidden" name="location" value="{{ $appointment->location }}">
                                                <input type="hidden" name="notes" value="{{ $appointment->notes }}">
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous?')">Annuler</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-3 px-4 text-center text-gray-500">Aucun rendez-vous planifié</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Actions</h3>

                    <div class="flex space-x-4">
                        <button type="button" class="px-4 py-2 bg-yellow-500 text-white rounded-md text-sm font-medium hover:bg-yellow-600">
                            Envoyer un email de rappel
                        </button>

                        <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium hover:bg-red-700">
                            Désactiver le compte
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Appointment Modal -->
    <div id="addAppointmentModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="fixed inset-0 bg-black opacity-50" id="add-modal-overlay"></div>
        <div class="bg-white rounded-lg shadow-xl overflow-hidden w-full max-w-2xl z-10 relative">
            <div class="flex justify-between items-center p-6 bg-gray-50 border-b">
                <h3 class="text-lg font-medium text-gray-900">Planifier un rendez-vous</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500" id="close-add-modal">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('admin.appointments.store') }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="staff_id" class="block text-sm font-medium text-gray-700">Examinateur</label>
                        <select id="staff_id" name="staff_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                            <option value="">Sélectionnez un examinateur</option>
                            @foreach(App\Models\User::where('role', 'staff')->get() as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="scheduled_at" class="block text-sm font-medium text-gray-700">Date et Heure</label>
                        <input type="datetime-local" name="scheduled_at" id="scheduled_at" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                    </div>

                    <div class="mb-4 md:col-span-2">
                        <label for="location" class="block text-sm font-medium text-gray-700">Lieu</label>
                        <input type="text" name="location" id="location" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
                </div>

                <div class="mt-5 sm:mt-6 flex justify-end space-x-2">
                    <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600" id="cancel-add-button">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Planifier
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Appointment Modal -->
    <div id="editAppointmentModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="fixed inset-0 bg-black opacity-50" id="edit-modal-overlay"></div>
        <div class="bg-white rounded-lg shadow-xl overflow-hidden w-full max-w-2xl z-10 relative">
            <div class="flex justify-between items-center p-6 bg-gray-50 border-b">
                <h3 class="text-lg font-medium text-gray-900">Modifier le rendez-vous</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500" id="close-edit-modal">
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
                    <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600" id="cancel-edit-button">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Quiz Result Details Modal -->
    <div id="quizResultModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="fixed inset-0 bg-black opacity-50" id="quiz-modal-overlay"></div>
        <div class="bg-white rounded-lg shadow-xl overflow-hidden w-full max-w-3xl z-10 relative">
            <div class="flex justify-between items-center p-6 bg-gray-50 border-b">
                <h3 class="text-lg font-medium text-gray-900">Détails du résultat de quiz</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500" id="close-quiz-modal">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="quizResultContent" class="p-6">
                <!-- Content will be loaded dynamically -->
                <div class="flex items-center justify-center h-32">
                    <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add Appointment Modal
            const addModal = document.getElementById('addAppointmentModal');
            const closeAddModalBtn = document.getElementById('close-add-modal');
            const cancelAddBtn = document.getElementById('cancel-add-button');
            const addOverlay = document.getElementById('add-modal-overlay');

            window.openAddAppointmentModal = function() {
                addModal.classList.remove('hidden');
            };

            function closeAddModal() {
                addModal.classList.add('hidden');
            }

            closeAddModalBtn.addEventListener('click', closeAddModal);
            cancelAddBtn.addEventListener('click', closeAddModal);
            addOverlay.addEventListener('click', closeAddModal);

            // Edit Appointment Modal
            const editModal = document.getElementById('editAppointmentModal');
            const closeEditModalBtn = document.getElementById('close-edit-modal');
            const cancelEditBtn = document.getElementById('cancel-edit-button');
            const editOverlay = document.getElementById('edit-modal-overlay');
            const editForm = document.getElementById('editAppointmentForm');

            window.openEditAppointmentModal = function(appointmentId) {
                // Set the form action URL with the appointment ID
                editForm.action = `/admin/appointments/${appointmentId}`;

                // In a real app, you would fetch the appointment data and populate the form
                // For now, we'll just show the modal
                editModal.classList.remove('hidden');
            };

            function closeEditModal() {
                editModal.classList.add('hidden');
            }

            closeEditModalBtn.addEventListener('click', closeEditModal);
            cancelEditBtn.addEventListener('click', closeEditModal);
            editOverlay.addEventListener('click', closeEditModal);

            // Quiz Result Modal
            const quizModal = document.getElementById('quizResultModal');
            const closeQuizModalBtn = document.getElementById('close-quiz-modal');
            const quizOverlay = document.getElementById('quiz-modal-overlay');
            const quizContent = document.getElementById('quizResultContent');

            window.openQuizResultModal = function(resultId) {
                quizModal.classList.remove('hidden');

                // In a real app, you would load the quiz result details here
                // For example:
                // fetch(`/admin/quiz-results/${resultId}`)
                //     .then(response => response.html())
                //     .then(data => {
                //         quizContent.innerHTML = data;
                //     });

                // For now, we'll just show a placeholder
                setTimeout(() => {
                    quizContent.innerHTML = `
                        <div class="bg-gray-50 p-4 rounded-lg mb-4">
                            <div class="flex justify-between">
                                <div>
                                    <h4 class="font-medium">Quiz #${resultId}</h4>
                                    <p class="text-sm text-gray-500">Complété le 01/01/2025</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-green-600">85%</div>
                                    <div class="text-sm text-gray-500">Score obtenu</div>
                                </div>
                            </div>
                        </div>

                        <h4 class="font-medium mb-4">Réponses</h4>

                        <div class="space-y-4">
                            <div class="border rounded-lg overflow-hidden">
                                <div class="bg-gray-100 px-4 py-2 font-medium">Question 1</div>
                                <div class="p-4">
                                    <p class="mb-2">Qu'est-ce que Laravel?</p>
                                    <div class="ml-4 space-y-2">
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center mr-2">A</div>
                                            <span>Un framework JavaScript</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center mr-2">B</div>
                                            <span class="font-medium">Un framework PHP</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center mr-2">C</div>
                                            <span>Un système de base de données</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center mr-2">D</div>
                                            <span>Un langage de programmation</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="border rounded-lg overflow-hidden">
                                <div class="bg-gray-100 px-4 py-2 font-medium">Question 2</div>
                                <div class="p-4">
                                    <p class="mb-2">Laquelle des commandes suivantes n'est pas une commande Artisan de Laravel?</p>
                                    <div class="ml-4 space-y-2">
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center mr-2">A</div>
                                            <span>php artisan migrate</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center mr-2">B</div>
                                            <span>php artisan serve</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center mr-2">C</div>
                                            <span class="font-medium">php artisan compile</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center mr-2">D</div>
                                            <span>php artisan tinker</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }, 500);
            };

            function closeQuizModal() {
                quizModal.classList.add('hidden');
            }

            closeQuizModalBtn.addEventListener('click', closeQuizModal);
            quizOverlay.addEventListener('click', closeQuizModal);
        });
    </script>
</x-app-layout>
