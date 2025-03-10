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
                            <p>Time Taken: {{ gmdate("H:i:s", $userQuiz->time_taken) }}</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="bg-gray-100 p-4 rounded-lg">
                                <h4 class="font-semibold mb-2">Quiz Summary:</h4>
                                <ul class="space-y-1">
                                    <li><span class="font-medium">Completed On:</span> {{ $userQuiz->completed_at->format('F j, Y, g:i a') }}</li>
                                    <li><span class="font-medium">Total Questions:</span> {{ $quiz->questions->count() }}</li>
                                    <li><span class="font-medium">Result:</span> {{ $userQuiz->passed ? 'Pass' : 'Fail' }}</li>
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
                        
                        <h4 class="font-semibold text-lg mb-4">Question Review:</h4>
                        
                        <div class="space-y-6">
                            @php
                                $answers = json_decode($userQuiz->answers, true);
                            @endphp
                            
                            @foreach($quiz->questions as $index => $question)
                                <div class="border rounded-lg overflow-hidden">
                                    <div class="bg-gray-100 px-4 py-2 font-semibold">
                                        Question {{ $index + 1 }} ({{ $question->points }} {{ $question->points == 1 ? 'point' : 'points' }})
                                    </div>
                                    <div class="p-4">
                                        <p class="mb-4">{{ $question->question_text }}</p>
                                        
                                        @php
                                            $options = json_decode($question->options, true);
                                            $userAnswer = $answers[$question->id] ?? null;
                                            $isCorrect = $userAnswer === $question->correct_answer;
                                        @endphp
                                        
                                        <div class="ml-4 space-y-2">
                                            @foreach($options as $key => $option)
                                                <div class="flex items-center">
                                                    @if($key === $question->correct_answer && $key === $userAnswer)
                                                        <!-- Correct answer -->
                                                        <div class="w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center mr-2">{{ $key }}</div>
                                                        <span class="font-medium">{{ $option }} <span class="text-green-600">(Correct)</span></span>
                                                    @elseif($key === $question->correct_answer)
                                                        <!-- User didn't select correct answer -->
                                                        <div class="w-6 h-6 rounded-full bg-green-200 text-green-800 flex items-center justify-center mr-2">{{ $key }}</div>
                                                        <span>{{ $option }} <span class="text-green-600">(Correct Answer)</span></span>
                                                    @elseif($key === $userAnswer)
                                                        <!-- User selected wrong answer -->
                                                        <div class="w-6 h-6 rounded-full bg-red-500 text-white flex items-center justify-center mr-2">{{ $key }}</div>
                                                        <span class="font-medium">{{ $option }} <span class="text-red-600">(Your Answer)</span></span>
                                                    @else
                                                        <!-- Other options -->
                                                        <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center mr-2">{{ $key }}</div>
                                                        <span>{{ $option }}</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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