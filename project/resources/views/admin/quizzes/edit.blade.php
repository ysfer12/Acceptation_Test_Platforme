<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Modifier le Quiz') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.quizzes.questions', $quiz) }}" class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-medium hover:bg-green-700">
                    Gérer les Questions
                </a>
                <a href="{{ route('admin.quizzes') }}" class="px-4 py-2 bg-gray-300 rounded-md text-gray-800 text-sm font-medium hover:bg-gray-400">
                    Retour à la liste
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('status'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.quizzes.update', $quiz) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Titre</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $quiz->title) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                            @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>{{ old('description', $quiz->description) }}</textarea>
                            @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="mb-4">
                                <label for="time_limit" class="block text-sm font-medium text-gray-700">Limite de temps (minutes)</label>
                                <input type="number" name="time_limit" id="time_limit" value="{{ old('time_limit', $quiz->time_limit) }}" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                @error('time_limit')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="passing_score" class="block text-sm font-medium text-gray-700">Score de réussite (%)</label>
                                <input type="number" name="passing_score" id="passing_score" value="{{ old('passing_score', $quiz->passing_score) }}" min="1" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                @error('passing_score')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-between">
                            <div>
                                <form action="{{ route('admin.quizzes.toggle', $quiz) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-{{ $quiz->is_active ? 'yellow' : 'green' }}-600 text-white rounded-md hover:bg-{{ $quiz->is_active ? 'yellow' : 'green' }}-700">
                                        {{ $quiz->is_active ? 'Désactiver le Quiz' : 'Activer le Quiz' }}
                                    </button>
                                </form>
                            </div>

                            <div class="flex space-x-3">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    Mettre à jour le Quiz
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quiz Status -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Statistiques du Quiz</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm text-gray-500">Nombre de questions</div>
                            <div class="text-xl font-bold">{{ $quiz->questions->count() }}</div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm text-gray-500">Quiz terminés</div>
                            <div class="text-xl font-bold">{{ $quiz->userQuizzes()->whereNotNull('completed_at')->count() }}</div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm text-gray-500">Taux de réussite</div>
                            @php
                                $completed = $quiz->userQuizzes()->whereNotNull('completed_at')->count();
                                $passed = $quiz->userQuizzes()->where('passed', true)->count();
                                $rate = $completed > 0 ? round(($passed / $completed) * 100) : 0;
                            @endphp
                            <div class="text-xl font-bold">{{ $rate }}%</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2 text-red-600">Zone Dangereuse</h3>

                    <div class="flex justify-between items-center">
                        <div>
                            <h4 class="font-medium">Supprimer ce quiz</h4>
                            <p class="text-sm text-gray-500">Une fois supprimé, toutes les données associées seront définitivement perdues.</p>
                        </div>

                        <form action="{{ route('admin.quizzes.destroy', $quiz) }}" method="POST" onsubmit="return confirm('Êtes-vous certain de vouloir supprimer ce quiz? Cette action est irréversible.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                Supprimer le Quiz
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
