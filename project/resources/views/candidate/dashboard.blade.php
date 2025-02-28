<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Candidate Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-6 shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Welcome Card -->
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-8 text-white">
                    <h3 class="text-2xl font-bold mb-2">Welcome, {{ auth()->user()->first_name ?? auth()->user()->name }}!</h3>
                    <p class="text-blue-100 mb-4">{{ now()->format('l, F j, Y') }}</p>
                    <p class="text-blue-100">Track your recruitment process and stay updated on your application status.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Application Status Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg col-span-2">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Application Progress</h3>

                        <div class="relative">
                            <!-- Timeline track -->
                            <div class="absolute h-full w-0.5 bg-gray-200 left-9 top-0"></div>

                            <!-- Step 1: Profile -->
                            <div class="relative flex items-start mb-8">
                                <div class="flex items-center justify-center w-20 h-10">
                                    <div class="w-8 h-8 rounded-full {{ auth()->user()->id_card_path ? 'bg-green-500' : 'bg-blue-500' }} flex items-center justify-center shadow-md">
                                        @if(auth()->user()->id_card_path)
                                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        @else
                                            <span class="text-white font-medium">1</span>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-800">Complete Profile</h5>
                                    <p class="text-sm {{ auth()->user()->id_card_path ? 'text-green-600' : 'text-blue-600' }}">
                                        {{ auth()->user()->id_card_path ? 'Completed' : 'In Progress' }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">Upload required documents and provide personal information</p>
                                </div>
                            </div>

                            <!-- Step 2: Online Assessment -->
                            <div class="relative flex items-start mb-8">
                                <div class="flex items-center justify-center w-20 h-10">
                                    <div class="w-8 h-8 rounded-full {{ isset($passedQuiz) && $passedQuiz ? 'bg-green-500' : (auth()->user()->id_card_path ? 'bg-blue-500' : 'bg-gray-300') }} flex items-center justify-center shadow-md">
                                        @if(isset($passedQuiz) && $passedQuiz)
                                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        @else
                                            <span class="text-white font-medium">2</span>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-800">Online Assessment</h5>
                                    <p class="text-sm {{ isset($passedQuiz) && $passedQuiz ? 'text-green-600' : (auth()->user()->id_card_path ? 'text-blue-600' : 'text-gray-500') }}">
                                        @if(isset($passedQuiz) && $passedQuiz)
                                            Passed with {{ $passedQuiz->score }}%
                                        @elseif(auth()->user()->id_card_path)
                                            Ready to start
                                        @else
                                            Complete your profile first
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">Complete the online skills assessment</p>
                                </div>
                            </div>

                            <!-- Step 3: In-Person Assessment -->
                            <div class="relative flex items-start mb-8">
                                <div class="flex items-center justify-center w-20 h-10">
                                    <div class="w-8 h-8 rounded-full {{ (isset($passedQuiz) && $passedQuiz && isset($userAppointment)) ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center shadow-md">
                                        <span class="text-white font-medium">3</span>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-800">In-Person Assessment</h5>
                                    <p class="text-sm {{ (isset($passedQuiz) && $passedQuiz && isset($userAppointment)) ? 'text-green-600' : 'text-gray-500' }}">
                                        @if(isset($passedQuiz) && $passedQuiz)
                                            @if(isset($userAppointment))
                                                Scheduled for {{ $userAppointment->scheduled_at->format('F j, Y, g:i a') }}
                                            @else
                                                Waiting for scheduling
                                            @endif
                                        @else
                                            Pass the online assessment first
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">Face-to-face technical and administrative evaluation</p>
                                </div>
                            </div>

                            <!-- Step 4: Final Decision -->
                            <div class="relative flex items-start">
                                <div class="flex items-center justify-center w-20 h-10">
                                    <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center shadow-md">
                                        <span class="text-white font-medium">4</span>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-800">Final Decision</h5>
                                    <p class="text-sm text-gray-500">Complete previous steps</p>
                                    <p class="text-xs text-gray-500 mt-1">Receive the final decision on your application</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Card -->
                <div class="flex flex-col space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6 h-full flex flex-col">
                            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Next Steps</h3>

                            @if(!auth()->user()->id_card_path)
                                <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 mb-4 rounded-md">
                                    <p class="font-bold">Complete your profile</p>
                                    <p class="text-sm mt-1">Please upload your ID card and complete your personal information.</p>
                                </div>

                                <div class="mt-auto">
                                    <a href="{{ route('profile.edit') }}" class="w-full inline-flex justify-center items-center px-4 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Complete Profile
                                    </a>
                                </div>
                            @elseif(!isset($passedQuiz) || !$passedQuiz)
                                <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4 rounded-md">
                                    <p class="font-bold">Take the online assessment</p>
                                    <p class="text-sm mt-1">Complete the online assessment to proceed with your application.</p>
                                </div>

                                <div class="mt-auto">
                                    <a href="{{ route('quiz.index') }}" class="w-full inline-flex justify-center items-center px-4 py-3 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        Take Assessment
                                    </a>
                                </div>
                            @else
                                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md">
                                    <p class="font-bold">Assessment completed</p>
                                    <p class="text-sm mt-1">You've passed the online assessment. Our team will be in touch about scheduling your in-person assessment.</p>
                                </div>

                                <div class="mt-auto">
                                    <a href="{{ route('assessment.status') }}" class="w-full inline-flex justify-center items-center px-4 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        View Assessment Status
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Help & Resources Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Help & Resources</h3>

                            <ul class="space-y-3">
                                <li>
                                    <a href="#" class="text-indigo-600 hover:text-indigo-800 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                        </svg>
                                        FAQs
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="text-indigo-600 hover:text-indigo-800 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                        </svg>
                                        Contact Support
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="text-indigo-600 hover:text-indigo-800 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Documentation
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">About the Recruitment Process</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="p-4 border border-gray-200 rounded-lg">
                            <div class="text-blue-600 flex justify-center mb-3">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h4 class="font-medium text-center mb-2">Documentation</h4>
                            <p class="text-sm text-gray-600 text-center">You'll need to submit all required documents including your ID card and personal information.</p>
                        </div>

                        <div class="p-4 border border-gray-200 rounded-lg">
                            <div class="text-blue-600 flex justify-center mb-3">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                            </div>
                            <h4 class="font-medium text-center mb-2">Online Assessment</h4>
                            <p class="text-sm text-gray-600 text-center">Complete an online skills assessment to evaluate your technical knowledge and problem-solving abilities.</p>
                        </div>

                        <div class="p-4 border border-gray-200 rounded-lg">
                            <div class="text-blue-600 flex justify-center mb-3">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h4 class="font-medium text-center mb-2">In-Person Assessment</h4>
                            <p class="text-sm text-gray-600 text-center">If you pass the online assessment, you'll be invited for an in-person technical and administrative evaluation.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
