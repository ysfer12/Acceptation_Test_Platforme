<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gérer les Questions') }}: {{ $quiz->title }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                    Modifier le Quiz
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

            <!-- Quiz Info Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Informations du Quiz</h3>
                            <div class="text-sm text-gray-600">{{ $quiz->description }}</div>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="bg-gray-50 p-3 rounded-lg text-center">
                                <div class="font-bold text-lg">{{ $quiz->time_limit }}</div>
                                <div class="text-xs text-gray-500">minutes</div>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg text-center">
                                <div class="font-bold text-lg">{{ $quiz->passing_score }}%</div>
                                <div class="text-xs text-gray-500">score de réussite</div>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg text-center">
                                <div class="font-bold text-lg">{{ $quiz->questions->count() }}</div>
                                <div class="text-xs text-gray-500">questions</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Question Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Ajouter une Nouvelle Question</h3>

                    <form action="{{ route('admin.quizzes.questions.store', $quiz) }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="question_text" class="block text-sm font-medium text-gray-700">Texte de la Question</label>
                            <textarea name="question_text" id="question_text" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>{{ old('question_text') }}</textarea>
                            @error('question_text')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="option_a" class="block text-sm font-medium text-gray-700">Option A</label>
                                <input type="text" name="option_a" id="option_a" value="{{ old('option_a') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                @error('option_a')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="option_b" class="block text-sm font-medium text-gray-700">Option B</label>
                                <input type="text" name="option_b" id="option_b" value="{{ old('option_b') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                @error('option_b')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="option_c" class="block text-sm font-medium text-gray-700">Option C</label>
                                <input type="text" name="option_c" id="option_c" value="{{ old('option_c') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                @error('option_c')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="option_d" class="block text-sm font-medium text-gray-700">Option D</label>
                                <input type="text" name="option_d" id="option_d" value="{{ old('option_d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                @error('option_d')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="correct_answer" class="block text-sm font-medium text-gray-700">Réponse Correcte</label>
                                <select name="correct_answer" id="correct_answer" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                    <option value="">Sélectionnez la réponse correcte</option>
                                    <option value="A" {{ old('correct_answer') == 'A' ? 'selected' : '' }}>Option A</option>
                                    <option value="B" {{ old('correct_answer') == 'B' ? 'selected' : '' }}>Option B</option>
                                    <option value="C" {{ old('correct_answer') == 'C' ? 'selected' : '' }}>Option C</option>
                                    <option value="D" {{ old('correct_answer') == 'D' ? 'selected' : '' }}>Option D</option>
                                </select>
                                @error('correct_answer')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="points" class="block text-sm font-medium text-gray-700">Points</label>
                                <input type="number" name="points" id="points" value="{{ old('points', 1) }}" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                @error('points')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Ajouter la Question
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Questions List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Questions du Quiz ({{ $quiz->questions->count() }})</h3>

                    @if($quiz->questions->count() > 0)
                        <div class="space-y-6">
                            @foreach($quiz->questions as $index => $question)
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-medium">Question {{ $index + 1 }} ({{ $question->points }} points)</h4>
                                            <p class="mt-1">{{ $question->question_text }}</p>

                                            <div class="mt-3 space-y-1">
                                                @php
                                                    $options = json_decode($question->options, true);
                                                @endphp
                                                @foreach($options as $key => $option)
                                                    <div class="flex items-center">
                                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full {{ $key == $question->correct_answer ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700' }} mr-2 text-xs font-medium">
                                                            {{ $key }}
                                                        </span>
                                                        <p>{{ $option }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <form action="{{ route('admin.quizzes.questions.destroy', $question) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette question?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p>Aucune question ajoutée à ce quiz.</p>
                            <p class="text-sm mt-2">Utilisez le formulaire ci-dessus pour ajouter des questions.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
