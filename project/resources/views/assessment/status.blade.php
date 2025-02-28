<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assessment Status') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-6">Your Application Progress</h3>

                    @if($latestQuiz)
                        <div class="mb-8">
                            <div class="bg-{{ $latestQuiz->passed ? 'green' : 'yellow' }}-100 border-l-4 border-{{ $latestQuiz->passed ? 'green' : 'yellow' }}-500 text-{{ $latestQuiz->passed ? 'green' : 'yellow' }}-700 p-4 mb-4">
                                <p class="font-bold">Online Assessment Status:
                                    @if($latestQuiz->passed)
                                        <span class="text-green-600">Passed</span>
                                    @else
                                        <span class="text-yellow-600">Not Passed</span>
                                    @endif
                                </p>
                                <p>Score: {{ $latestQuiz->score }}%</p>
                                <p>Completed on: {{ $latestQuiz->completed_at->format('F j, Y, g:i a') }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="bg-gray-50 rounded-lg p-6">
                        <h4 class="text-lg font-medium mb-4">Application Journey</h4>

                        <div class="relative">
                            <!-- Timeline track -->
                            <div class="absolute h-full w-0.5 bg-gray-200 left-9 top-0"></div>

                            <!-- Step 1: Profile -->
                            <div class="relative flex items-start mb-8">
                                <div class="flex items-center justify-center w-20 h-10">
                                    <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="font-medium">Complete Profile</h5>
                                    <p class="text-sm text-green-600">Completed</p>
                                </div>
                            </div>

                            <!-- Step 2: Online Assessment -->
                            <div class="relative flex items-start mb-8">
                                <div class="flex items-center justify-center w-20 h-10">
                                    <div class="w-6 h-6 rounded-full {{ $latestQuiz ? 'bg-green-500' : 'bg-blue-500' }} flex items-center justify-center">
                                        @if($latestQuiz)
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        @else
                                            <span class="text-white text-xs">2</span>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <h5 class="font-medium">Online Assessment</h5>
                                    <p class="text-sm {{ $latestQuiz ? ($latestQuiz->passed ? 'text-green-600' : 'text-yellow-600') : 'text-gray-500' }}">
                                        @if($latestQuiz)
                                            @if($latestQuiz->passed)
                                                Passed with {{ $latestQuiz->score }}%
                                            @else
                                                Not passed - Score: {{ $latestQuiz->score }}%
                                            @endif
                                        @else
                                            Pending
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Step 3: In-Person Assessment -->
                            <div class="relative flex items-start mb-8">
                                <div class="flex items-center justify-center w-20 h-10">
                                    <div class="w-6 h-6 rounded-full {{ $appointment ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center">
                                        <span class="text-white text-xs">3</span>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="font-medium">In-Person Assessment</h5>
                                    <p class="text-sm text-gray-500">
                                        @if($appointment)
                                            Scheduled for {{ $appointment->scheduled_at->format('F j, Y, g:i a') }}
                                        @elseif($latestQuiz && $latestQuiz->passed)
                                            Waiting for scheduling
                                        @else
                                            @if($latestQuiz)
                                                Please retake the online assessment
                                            @else
                                                Complete the online assessment first
                                            @endif
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Step 4: Final Decision -->
                            <div class="relative flex items-start">
                                <div class="flex items-center justify-center w-20 h-10">
                                    <div class="w-6 h-6 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-white text-xs">4</span>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="font-medium">Final Decision</h5>
                                    <p class="text-sm text-gray-500">Complete previous steps</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($latestQuiz && !$latestQuiz->passed)
                        <div class="mt-6">
                            <a href="{{ route('quiz.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Retake Assessment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
