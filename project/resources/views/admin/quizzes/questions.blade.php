<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Questions') }}: {{ $quiz->title }}
            </h2>
            <a href="{{ route('admin.quizzes') }}" class="px-4 py-2 bg-gray-300 rounded-md text-gray-800 text-sm font-medium hover:bg-gray-400">
                Back to Quizzes
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

            <!-- Add New Question Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Add New Question</h3>

                    <form action="{{ route('admin.quizzes.questions.store', $quiz) }}" method="POST">
                        @csrf
                        
                        <div class="mb-6">
                            <label for="question_text" class="block text-sm font-medium text-gray-700 mb-1">Question</label>
                            <textarea id="question_text" name="question_text" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>{{ old('question_text') }}</textarea>
                            @error('question_text')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="option_a" class="block text-sm font-medium text-gray-700 mb-1">Option A</label>
                                <input type="text" id="option_a" name="option_a" value="{{ old('option_a') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                @error('option_a')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="option_b" class="block text-sm font-medium text-gray-700 mb-1">Option B</label>
                                <input type="text" id="option_b" name="option_b" value="{{ old('option_b') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                @error('option_b')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="option_c" class="block text-sm font-medium text-gray-700 mb-1">Option C</label>
                                <input type="text" id="option_c" name="option_c" value="{{ old('option_c') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                @error('option_c')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="option_d" class="block text-sm font-medium text-gray-700 mb-1">Option D</label>
                                <input type="text" id="option_d" name="option_d" value="{{ old('option_d') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                @error('option_d')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="correct_answer" class="block text-sm font-medium text-gray-700 mb-1">Correct Answer</label>
                                <select id="correct_answer" name="correct_answer" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                    <option value="">-- Select correct answer --</option>
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
                                <label for="points" class="block text-sm font-medium text-gray-700 mb-1">Points</label>
                                <input type="number" id="points" name="points" value="{{ old('points', 1) }}" min="1" max="10" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                @error('points')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Add Question
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Question List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold border-b pb-2">Questions ({{ $quiz->questions->count() }})</h3>
                        <div class="text-sm text-gray-500">
                            Points total: {{ $quiz->questions->sum('points') }}
                        </div>
                    </div>

                    @if($quiz->questions->count() > 0)
                        <div class="space-y-6">
                            @foreach($quiz->questions as $index => $question)
                                <div class="border rounded-lg p-4 relative">
                                    <div class="absolute top-4 right-4">
                                        <form action="{{ route('admin.quizzes.questions.destroy', $question) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this question?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="mb-3">
                                        <span class="font-semibold">Question {{ $index + 1 }}:</span> {{ $question->question_text }}
                                        <span class="ml-2 text-sm text-gray-500">({{ $question->points }} {{ $question->points == 1 ? 'point' : 'points' }})</span>
                                    </div>
                                    
                                    @php
                                        $options = json_decode($question->options, true);
                                    @endphp
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 ml-6">
                                        @foreach($options as $key => $option)
                                            <div class="flex items-center">
                                                <div class="w-6 h-6 rounded-full {{ $key == $question->correct_answer ? 'bg-green-500 text-white' : 'bg-gray-200' }} flex items-center justify-center mr-2">
                                                    {{ $key }}
                                                </div>
                                                <span>{{ $option }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            No questions have been added yet. Add your first question above.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>