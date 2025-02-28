<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Taking Quiz') }}: {{ $quiz->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6 flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Quiz Questions</h3>
                    </div>

                    <form id="quizForm" method="POST" action="{{ route('quiz.submit', $userQuiz) }}">
                        @csrf

                        @foreach ($questions as $index => $question)
                            <div class="mb-8 p-6 bg-gray-50 rounded-lg">
                                <h4 class="font-medium text-lg mb-4">{{ $index + 1 }}. {{ $question->question_text }}</h4>

                                <div class="space-y-3">
                                    @foreach (json_decode($question->options) as $key => $option)
                                        <div class="flex items-center">
                                            <input type="radio" id="q{{ $question->id }}_{{ $key }}" name="answers[{{ $question->id }}]" value="{{ $key }}" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                            <label for="q{{ $question->id }}_{{ $key }}" class="ml-3 block text-gray-700">
                                                {{ $option }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Submit Quiz
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
