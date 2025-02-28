<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quiz') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('message'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('message') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($quiz)
                        <div class="mb-6">
                            <h3 class="text-2xl font-bold mb-2">{{ $quiz->title }}</h3>
                            <p class="text-gray-600 mb-4">{{ $quiz->description }}</p>

                            <div class="bg-gray-100 p-4 rounded-lg mb-6">
                                <h4 class="font-semibold mb-2">Quiz Details:</h4>
                                <ul class="list-disc pl-5 space-y-1">
                                    <li>Time Limit: {{ $quiz->time_limit }} minutes</li>
                                    <li>Passing Score: {{ $quiz->passing_score }}%</li>
                                    <li>Number of Questions: {{ $quiz->questions->count() ?: 'Loading...' }}</li>
                                </ul>
                            </div>

                            @if ($userQuiz && $userQuiz->completed_at)
                                <div class="mb-4">
                                    <div class="bg-{{ $userQuiz->passed ? 'green' : 'red' }}-100 border-l-4 border-{{ $userQuiz->passed ? 'green' : 'red' }}-500 text-{{ $userQuiz->passed ? 'green' : 'red' }}-700 p-4">
                                        <p class="font-bold">{{ $userQuiz->passed ? 'Quiz Passed!' : 'Quiz Failed!' }}</p>
                                        <p>Your score: {{ $userQuiz->score }}%</p>
                                    </div>

                                    <div class="mt-4">
                                        <a href="{{ route('quiz.results', $userQuiz->id) }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                            View Results
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4">
                                    <p class="font-bold">Important:</p>
                                    <ul class="list-disc pl-5">
                                        <li>Once started, you cannot pause the quiz.</li>
                                        <li>Ensure you have a stable internet connection.</li>
                                        <li>The quiz will auto-submit when time expires.</li>
                                    </ul>
                                </div>

                                <form method="POST" action="{{ route('quiz.start', $quiz) }}">
                                    @csrf
                                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        Start Quiz
                                    </button>
                                </form>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-xl font-medium text-gray-900">No quizzes available</h3>
                            <p class="mt-1 text-sm text-gray-500">Please check back later for available quizzes.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
