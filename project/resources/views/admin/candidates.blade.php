<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestion des Candidats') }}
            </h2>
            <a href="{{ route('admin.appointments') }}" class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-medium hover:bg-green-700">
                Planifier un rendez-vous
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

            <!-- Vue d'ensemble des Candidats -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Vue d'ensemble</h3>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                            <span class="block text-2xl font-bold text-blue-700">{{ $candidates->total() }}</span>
                            <span class="text-sm text-gray-600">Candidats totaux</span>
                        </div>

                        <div class="bg-green-50 rounded-lg p-4 border border-green-100">
                            <span class="block text-2xl font-bold text-green-700">{{ $candidates->where('email_verified_at', '!=', null)->count() }}</span>
                            <span class="text-sm text-gray-600">Emails vérifiés</span>
                        </div>

                        <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-100">
                            <span class="block text-2xl font-bold text-yellow-700">{{ $candidates->where(function($q) {
                                $q->whereNull('first_name')
                                  ->orWhereNull('last_name')
                                  ->orWhereNull('birth_date')
                                  ->orWhereNull('phone')
                                  ->orWhereNull('address')
                                  ->orWhereNull('id_card_path');
                            })->count() }}</span>
                            <span class="text-sm text-gray-600">Profils incomplets</span>
                        </div>

                        <div class="bg-purple-50 rounded-lg p-4 border border-purple-100">
                            <span class="block text-2xl font-bold text-purple-700">{{ $candidates->where('has_passed_quiz', '>', 0)->count() }}</span>
                            <span class="text-sm text-gray-600">Quiz réussis</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des Candidats -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
                        <h3 class="text-lg font-semibold">Liste des Candidats</h3>
                        <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2">
                            <form id="searchForm" class="flex items-center">
                                <div class="relative">
                                    <input type="text" id="searchInput" placeholder="Rechercher..." class="border border-gray-300 rounded-md pl-8 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <div class="absolute left-3 top-3 text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </form>
                            <select id="statusFilter" class="border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="all">Tous les statuts</option>
                                <option value="verified">Email vérifié</option>
                                <option value="pending">Email en attente</option>
                                <option value="complete">Profil complet</option>
                                <option value="incomplete">Profil incomplet</option>
                                <option value="passed">Quiz réussi</option>
                                <option value="notpassed">Quiz non réussi</option>
                            </select>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100">
                            <tr>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nom</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Téléphone</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Statut Email</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Profil</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Quiz</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date d'inscription</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                            @forelse($candidates as $candidate)
                                <tr class="candidate-row"
                                    data-name="{{ strtolower($candidate->first_name . ' ' . $candidate->last_name) }}"
                                    data-email="{{ strtolower($candidate->email) }}"
                                    data-email-status="{{ $candidate->email_verified_at ? 'verified' : 'pending' }}"
                                    data-profile-status="{{ ($candidate->first_name && $candidate->last_name && $candidate->birth_date && $candidate->phone && $candidate->address && $candidate->id_card_path) ? 'complete' : 'incomplete' }}"
                                    data-quiz-status="{{ $candidate->has_passed_quiz > 0 ? 'passed' : 'notpassed' }}">
                                    <td class="py-3 px-4">
                                        <div class="flex items-center">
                                            <div class="bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center mr-3">
                                                <span class="text-gray-600 font-semibold">
                                                    {{ substr($candidate->first_name ?? $candidate->name, 0, 1) }}
                                                </span>
                                            </div>
                                            <span>{{ $candidate->first_name ?? '' }} {{ $candidate->last_name ?? $candidate->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-sm">{{ $candidate->email }}</td>
                                    <td class="py-3 px-4 text-sm">{{ $candidate->phone ?? 'Non renseigné' }}</td>
                                    <td class="py-3 px-4">
                                        @if($candidate->email_verified_at)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Vérifié</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">En attente</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        @if($candidate->first_name && $candidate->last_name && $candidate->birth_date && $candidate->phone && $candidate->address && $candidate->id_card_path)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Complet</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Incomplet</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        @if($candidate->has_passed_quiz > 0)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Réussi</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Non réussi</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-sm">{{ $candidate->created_at->format('d/m/Y') }}</td>
                                    <td class="py-3 px-4">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.candidate.details', $candidate) }}" class="text-blue-600 hover:text-blue-900">Détails</a>
                                            <button type="button" onclick="openScheduleModal({{ $candidate->id }})" class="text-green-600 hover:text-green-900">Rendez-vous</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-3 px-4 text-center text-gray-500">Aucun candidat trouvé</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $candidates->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Appointment Modal -->
    <div id="scheduleModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="fixed inset-0 bg-black opacity-50" id="modal-overlay"></div>
        <div class="bg-white rounded-lg shadow-xl overflow-hidden w-full max-w-2xl z-10 relative">
            <div class="flex justify-between items-center p-6 bg-gray-50 border-b">
                <h3 class="text-lg font-medium text-gray-900">Planifier un rendez-vous</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500" id="close-modal">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('admin.appointments.store') }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" id="user_id" name="user_id" value="">

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
                    <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600" id="cancel-button">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Planifier
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Schedule Modal Functionality
            const scheduleModal = document.getElementById('scheduleModal');
            const closeModalBtn = document.getElementById('close-modal');
            const cancelBtn = document.getElementById('cancel-button');
            const overlay = document.getElementById('modal-overlay');
            const userIdInput = document.getElementById('user_id');

            window.openScheduleModal = function(candidateId) {
                userIdInput.value = candidateId;
                scheduleModal.classList.remove('hidden');
            };

            function closeModal() {
                scheduleModal.classList.add('hidden');
            }

            closeModalBtn.addEventListener('click', closeModal);
            cancelBtn.addEventListener('click', closeModal);
            overlay.addEventListener('click', closeModal);

            // Search Functionality
            const searchInput = document.getElementById('searchInput');
            const candidateRows = document.querySelectorAll('.candidate-row');

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();

                candidateRows.forEach(row => {
                    const name = row.dataset.name;
                    const email = row.dataset.email;

                    if (name.includes(searchTerm) || email.includes(searchTerm)) {
                        row.style.display = 'table-row';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            // Filter Functionality
            const statusFilter = document.getElementById('statusFilter');

            statusFilter.addEventListener('change', function() {
                const filterValue = this.value;

                candidateRows.forEach(row => {
                    if (filterValue === 'all') {
                        row.style.display = 'table-row';
                    } else if (filterValue === 'verified' || filterValue === 'pending') {
                        row.style.display = row.dataset.emailStatus === filterValue ? 'table-row' : 'none';
                    } else if (filterValue === 'complete' || filterValue === 'incomplete') {
                        row.style.display = row.dataset.profileStatus === filterValue ? 'table-row' : 'none';
                    } else if (filterValue === 'passed' || filterValue === 'notpassed') {
                        row.style.display = row.dataset.quizStatus === filterValue ? 'table-row' : 'none';
                    }
                });
            });
        });
    </script>
</x-app-layout>
