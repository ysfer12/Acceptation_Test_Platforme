<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quiz Results') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold mb-2">{{ $quiz->title }}</h3>

                        <div class="bg-{{ $userQuiz->passed ? 'green' : 'red' }}-100 border-l-4 border-{{ $userQuiz->passed ? 'green' : 'red' }}-500 text-{{ $userQuiz->passed ? 'green' : 'red' }}-700 p-4 mb-6">
                            <p class="font-bold text-lg">{{ $userQuiz->passed ? 'Congratulations! You passed the quiz.' : 'You did not pass the quiz.' }}</p>
                            <p>Your Score: {{ $userQuiz->score }}% (Passing Score: {{ $quiz->passing_score }}%)</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="bg-gray-100 p-4 rounded-lg">
                                <h4 class="font-semibold mb-2">Quiz Summary:</h4>
                                <ul class="space-y-1">
                                    <li><span class="font-medium">Completed On:</span> {{ $userQuiz->completed_at->format('F j, Y, g:i a') }}</li>
                                </ul>
                            </div>

                            @if ($userQuiz->passed)
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <h4 class="font-semibold mb-2">Next Steps:</h4>
                                    <p>You've qualified for the in-person assessment. Our team will contact you soon with scheduling details.</p>
                                </div>
                            @else
                                <div class="bg-yellow-50 p-4 rounded-lg">
                                    <h4 class="font-semibold mb-2">Next Steps:</h4>
                                    <p>You can retake the quiz after 24 hours. Review the material and try again.</p>
                                </div>
                            @endif
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('assessment.status') }}" class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700">
                                View Assessment Status
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
