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
                                    <div class="w-6 h-6 rounded-full {{ $appointment ? ($appointment->status === 'completed' ? 'bg-green-500' : 'bg-blue-500') : 'bg-gray-300' }} flex items-center justify-center">
                                        @if($appointment && $appointment->status === 'completed')
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        @else
                                            <span class="text-white text-xs">3</span>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <h5 class="font-medium">In-Person Assessment</h5>
                                    <p class="text-sm {{ $appointment ? ($appointment->status === 'completed' ? 'text-green-600' : 'text-blue-600') : 'text-gray-500' }}">
                                        @if($appointment)
                                            @if($appointment->status === 'completed')
                                                Completed on {{ $appointment->scheduled_at->format('F j, Y') }}
                                            @elseif($appointment->status === 'scheduled')
                                                Scheduled for {{ $appointment->scheduled_at->format('F j, Y, g:i a') }}
                                            @else
                                                {{ ucfirst($appointment->status) }}
                                            @endif
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
                                    <div class="w-6 h-6 rounded-full {{ isset($overallEvaluation) ? ($overallEvaluation->result === 'pass' ? 'bg-green-500' : ($overallEvaluation->result === 'fail' ? 'bg-red-500' : 'bg-yellow-500')) : 'bg-gray-300' }} flex items-center justify-center">
                                        @if(isset($overallEvaluation) && $overallEvaluation->result === 'pass')
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        @else
                                            <span class="text-white text-xs">4</span>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <h5 class="font-medium">Final Decision</h5>
                                    <p class="text-sm {{ isset($overallEvaluation) ? ($overallEvaluation->result === 'pass' ? 'text-green-600' : ($overallEvaluation->result === 'fail' ? 'text-red-600' : 'text-yellow-600')) : 'text-gray-500' }}">
                                        @if(isset($overallEvaluation))
                                            @if($overallEvaluation->result === 'pass')
                                                Congratulations! Your application has been approved.
                                            @elseif($overallEvaluation->result === 'fail')
                                                We regret to inform you that your application was not successful.
                                            @else
                                                Your application is still under review.
                                            @endif
                                        @else
                                            Complete previous steps
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($evaluations && $evaluations->count() > 0)
                        <div class="mt-8">
                            <h4 class="text-lg font-medium mb-4">Assessment Feedback</h4>
                            
                            <div class="space-y-4">
                                @foreach($evaluations as $evaluation)
                                    <div class="border rounded-lg overflow-hidden">
                                        <div class="bg-gray-50 px-4 py-2 flex justify-between items-center">
                                            <div>
                                                <span class="font-medium">{{ ucfirst($evaluation->type) }} Assessment</span>
                                                <span class="text-sm text-gray-500 ml-2">{{ $evaluation->created_at->format('M d, Y') }}</span>
                                            </div>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $evaluation->result === 'pass' ? 'bg-green-100 text-green-800' : 
                                                   ($evaluation->result === 'fail' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ ucfirst($evaluation->result) }}
                                            </span>
                                        </div>
                                        <div class="p-4">
                                            @if($evaluation->strengths)
                                                <div class="mb-2">
                                                    <div class="font-medium text-sm">Strengths:</div>
                                                    <p class="text-sm">{{ $evaluation->strengths }}</p>
                                                </div>
                                            @endif
                                            
                                            @if($evaluation->weaknesses)
                                                <div class="mb-2">
                                                    <div class="font-medium text-sm">Areas to Improve:</div>
                                                    <p class="text-sm">{{ $evaluation->weaknesses }}</p>
                                                </div>
                                            @endif
                                            
                                            @if($evaluation->comments)
                                                <div>
                                                    <div class="font-medium text-sm">Feedback:</div>
                                                    <p class="text-sm">{{ $evaluation->comments }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

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