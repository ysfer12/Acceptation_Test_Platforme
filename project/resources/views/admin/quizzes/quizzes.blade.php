<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestion des Quiz') }}
            </h2>
            <a href="{{ route('admin.quizzes.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                Nouveau Quiz
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

            <!-- Liste des Quiz -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Liste des Quiz</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100">
                            <tr>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Titre</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Description</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Questions</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Limite de temps</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Score de réussite</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Statut</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                            @forelse($quizzes as $quiz)
                                <tr>
                                    <td class="py-3 px-4">{{ $quiz->title }}</td>
                                    <td class="py-3 px-4 text-sm">{{ Str::limit($quiz->description, 50) }}</td>
                                    <td class="py-3 px-4 text-sm">{{ $quiz->questions_count ?? 0 }} questions</td>
                                    <td class="py-3 px-4 text-sm">{{ $quiz->time_limit }} minutes</td>
                                    <td class="py-3 px-4 text-sm">{{ $quiz->passing_score }}%</td>
                                    <td class="py-3 px-4">
                                        @if($quiz->is_active)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Actif</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactif</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex space-x-2">
                                            <form action="{{ route('admin.quizzes.toggle', $quiz) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-{{ $quiz->is_active ? 'yellow' : 'green' }}-600 hover:text-{{ $quiz->is_active ? 'yellow' : 'green' }}-900">
                                                    {{ $quiz->is_active ? 'Désactiver' : 'Activer' }}
                                                </button>
                                            </form>

                                            <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="text-blue-600 hover:text-blue-900">Modifier</a>

                                            <a href="{{ route('admin.quizzes.questions', $quiz) }}" class="text-purple-600 hover:text-purple-900">Questions</a>

                                            <form action="{{ route('admin.quizzes.destroy', $quiz) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce quiz?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-3 px-4 text-center text-gray-500">Aucun quiz trouvé</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
