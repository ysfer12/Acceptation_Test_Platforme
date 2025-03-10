<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Taking Quiz') }}: {{ $quiz->title }}
            </h2>
            <div id="timer-display" class="text-lg font-bold px-4 py-2 bg-gray-100 rounded-full">
                <span id="timer">{{ $quiz->time_limit }}:00</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-lg font-semibold">Quiz Progress</h3>
                            <span class="text-sm text-gray-600">Question <span id="current-question">1</span> of {{ $questions->count() }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div id="progress-bar" class="bg-blue-600 h-2.5 rounded-full" style="width: {{ 100 / $questions->count() }}%"></div>
                        </div>
                    </div>

                    <form id="quizForm" method="POST" action="{{ route('quiz.submit', $userQuiz) }}">
                        @csrf

                        <div class="question-container">
                            @foreach ($questions as $index => $question)
                                <div id="question-{{ $index + 1 }}" class="question-panel mb-8 p-6 bg-gray-50 rounded-lg {{ $index > 0 ? 'hidden' : '' }}">
                                    <h4 class="font-medium text-lg mb-4">{{ $index + 1 }}. {{ $question->question_text }}</h4>

                                    <div class="space-y-3">
                                        @php $options = json_decode($question->options, true); @endphp
                                        @foreach ($options as $key => $option)
                                            <div class="flex items-center">
                                                <input type="radio" 
                                                    id="q{{ $question->id }}_{{ $key }}" 
                                                    name="answers[{{ $question->id }}]" 
                                                    value="{{ $key }}" 
                                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 question-option"
                                                    data-question="{{ $index + 1 }}">
                                                <label for="q{{ $question->id }}_{{ $key }}" class="ml-3 block text-gray-700">
                                                    {{ $option }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="mt-6 flex {{ $index === $questions->count() - 1 ? 'justify-between' : 'justify-end' }}">
                                        @if($index > 0)
                                            <button type="button" class="prev-question px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                                Previous
                                            </button>
                                        @endif
                                        
                                        @if($index < $questions->count() - 1)
                                            <button type="button" class="next-question px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                                Next
                                            </button>
                                        @else
                                            <button type="submit" class="px-6 py-3 bg-green-600 text-white font-semibold rounded hover:bg-green-700">
                                                Submit Quiz
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 bg-gray-100 p-4 rounded-lg">
                            <h4 class="font-medium text-lg mb-4">Question Navigator</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($questions as $index => $question)
                                    <button type="button" 
                                        class="question-nav-btn w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center" 
                                        data-question="{{ $index + 1 }}">
                                        {{ $index + 1 }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Timer functionality
            let timeLeft = {{ $quiz->time_limit * 60 }}; // Convert minutes to seconds
            const timerDisplay = document.getElementById('timer');

            function updateTimer() {
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                timerDisplay.textContent = minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
                
                if (timeLeft <= 300) { // Less than 5 minutes
                    document.getElementById('timer-display').classList.add('bg-red-100', 'text-red-700');
                    document.getElementById('timer-display').classList.remove('bg-gray-100');
                }
                
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    document.getElementById('quizForm').submit();
                }
                
                timeLeft--;
            }

            const timerInterval = setInterval(updateTimer, 1000);
            
            // Question navigation
            let currentQuestion = 1;
            const totalQuestions = {{ $questions->count() }};
            const questionPanels = document.querySelectorAll('.question-panel');
            const questionNavBtns = document.querySelectorAll('.question-nav-btn');
            const nextButtons = document.querySelectorAll('.next-question');
            const prevButtons = document.querySelectorAll('.prev-question');
            const progressBar = document.getElementById('progress-bar');
            const currentQuestionDisplay = document.getElementById('current-question');

            function showQuestion(questionNum) {
                questionPanels.forEach(panel => panel.classList.add('hidden'));
                document.getElementById(`question-${questionNum}`).classList.remove('hidden');
                
                // Update progress bar and question display
                progressBar.style.width = `${(questionNum / totalQuestions) * 100}%`;
                currentQuestionDisplay.textContent = questionNum;
                
                // Update current question
                currentQuestion = questionNum;
                
                // Update nav buttons
                questionNavBtns.forEach(btn => {
                    btn.classList.remove('bg-blue-600', 'text-white');
                    if (parseInt(btn.dataset.question) === currentQuestion) {
                        btn.classList.add('bg-blue-600', 'text-white');
                    }
                    
                    // Check if question has been answered
                    const questionIndex = parseInt(btn.dataset.question);
                    const questionPanel = document.getElementById(`question-${questionIndex}`);
                    const radioButtons = questionPanel.querySelectorAll('input[type="radio"]');
                    let answered = false;
                    
                    radioButtons.forEach(radio => {
                        if (radio.checked) {
                            answered = true;
                        }
                    });
                    
                    if (answered && parseInt(btn.dataset.question) !== currentQuestion) {
                        btn.classList.add('bg-green-200');
                    }
                });
            }

            // Initialize question navigation
            questionNavBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    showQuestion(parseInt(this.dataset.question));
                });
            });

            // Next and previous buttons
            nextButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    if (currentQuestion < totalQuestions) {
                        showQuestion(currentQuestion + 1);
                    }
                });
            });

            prevButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    if (currentQuestion > 1) {
                        showQuestion(currentQuestion - 1);
                    }
                });
            });
            
            // Mark answered questions
            const questionOptions = document.querySelectorAll('.question-option');
            questionOptions.forEach(option => {
                option.addEventListener('change', function() {
                    const questionNum = parseInt(this.dataset.question);
                    const navBtn = document.querySelector(`.question-nav-btn[data-question="${questionNum}"]`);
                    
                    if (this.checked && navBtn) {
                        navBtn.classList.add('bg-green-200');
                    }
                });
            });
            
            // Initialize with the first question highlighted
            showQuestion(1);
        });
    </script>
</x-app-layout>