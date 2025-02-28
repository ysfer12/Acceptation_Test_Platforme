<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\UserQuiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    /**
     * Display the available quiz for candidates
     */
    public function index()
    {
        // Get the active quiz
        $quiz = Quiz::where('is_active', true)->first();

        // If no quiz exists, create a default one for demonstration
        if (!$quiz) {
            $quiz = Quiz::create([
                'title' => 'Candidate Assessment',
                'description' => 'This quiz assesses your skills and knowledge for the position.',
                'time_limit' => 30,
                'passing_score' => 70,
                'is_active' => true
            ]);
        }

        // Check if user has already taken the quiz
        $userQuiz = null;
        if ($quiz) {
            $userQuiz = UserQuiz::where([
                'user_id' => auth()->id(),
                'quiz_id' => $quiz->id
            ])->first();
        }

        return view('quiz.index', compact('quiz', 'userQuiz'));
    }

    /**
     * Start a new quiz
     */
    public function start(Quiz $quiz)
    {
        // Check if user has already taken this quiz
        $existingQuiz = UserQuiz::where([
            'user_id' => auth()->id(),
            'quiz_id' => $quiz->id
        ])->first();

        if ($existingQuiz && $existingQuiz->completed_at) {
            return redirect()->route('quiz.index')
                ->with('error', 'You have already completed this quiz.');
        }

        // If quiz is already started but not completed, continue from there
        if ($existingQuiz && !$existingQuiz->completed_at) {
            return redirect()->route('quiz.take', $existingQuiz->id);
        }

        // Start a new quiz
        $userQuiz = UserQuiz::create([
            'user_id' => auth()->id(),
            'quiz_id' => $quiz->id,
            'started_at' => now(),
            'answers' => json_encode([])
        ]);

        return redirect()->route('quiz.take', $userQuiz->id);
    }

    /**
     * Take the quiz
     */
    public function take(UserQuiz $userQuiz)
    {
        // Security check - only owner can access
        if ($userQuiz->user_id !== auth()->id()) {
            abort(403);
        }

        // If already completed, redirect to results
        if ($userQuiz->completed_at) {
            return redirect()->route('quiz.results', $userQuiz->id);
        }

        $quiz = $userQuiz->quiz;

        // For demo purposes, create some questions if none exist
        if ($quiz->questions()->count() === 0) {
            $this->createDemoQuestions($quiz);
        }

        $questions = $quiz->questions;

        return view('quiz.take', compact('userQuiz', 'quiz', 'questions'));
    }

    /**
     * Submit quiz answers
     */
    /**
     * Submit quiz answers
     */
    public function submit(Request $request, UserQuiz $userQuiz)
    {
        // Security check - only owner can submit
        if ($userQuiz->user_id !== auth()->id()) {
            abort(403);
        }

        // If already completed, redirect to results
        if ($userQuiz->completed_at) {
            return redirect()->route('quiz.results', $userQuiz->id);
        }

        $quiz = $userQuiz->quiz;
        $questions = $quiz->questions;

        // Get answers from the form
        $answers = $request->input('answers', []);

        // Calculate score (simplified for demo)
        $score = 85; // Demo score

        // Update user quiz
        $userQuiz->score = $score;
        $userQuiz->answers = json_encode($answers);
        $userQuiz->completed_at = now();
        $userQuiz->passed = $score >= $quiz->passing_score;
        $userQuiz->save();

        return redirect()->route('quiz.results', $userQuiz->id);
    }
    /**
     * Display quiz results
     */
    public function results(UserQuiz $userQuiz)
    {
        // Security check - only owner can view
        if ($userQuiz->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$userQuiz->completed_at) {
            return redirect()->route('quiz.take', $userQuiz->id);
        }

        $quiz = $userQuiz->quiz;

        return view('quiz.results', compact('userQuiz', 'quiz'));
    }

    /**
     * Create demo questions for a quiz
     */
    private function createDemoQuestions($quiz)
    {
        // Create demo questions for development/testing
        $questions = [
            [
                'question_text' => 'What is Laravel?',
                'options' => json_encode([
                    'A' => 'A JavaScript framework',
                    'B' => 'A PHP framework',
                    'C' => 'A database system',
                    'D' => 'A programming language'
                ]),
                'correct_answer' => 'B',
                'points' => 1
            ],
            [
                'question_text' => 'Which of the following is not a Laravel Artisan command?',
                'options' => json_encode([
                    'A' => 'php artisan migrate',
                    'B' => 'php artisan serve',
                    'C' => 'php artisan compile',
                    'D' => 'php artisan tinker'
                ]),
                'correct_answer' => 'C',
                'points' => 1
            ],
            [
                'question_text' => 'What does MVC stand for?',
                'options' => json_encode([
                    'A' => 'Model View Controller',
                    'B' => 'Most Valuable Code',
                    'C' => 'Multiple Virtual Connections',
                    'D' => 'Model Virtual Control'
                ]),
                'correct_answer' => 'A',
                'points' => 1
            ]
        ];

        foreach ($questions as $q) {
            $quiz->questions()->create($q);
        }
    }


    public function create()
    {
        return view('admin.quizzes.create');
    }

    /**
     * Store a new quiz
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'time_limit' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:1|max:100',
        ]);

        $quiz = Quiz::create($validated);

        return redirect()->route('admin.quizzes')
            ->with('status', 'Quiz créé avec succès.');
    }

    /**
     * Show the quiz edit form
     */
    public function edit(Quiz $quiz)
    {
        return view('admin.quizzes.edit', compact('quiz'));
    }

    /**
     * Update the quiz
     */
    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'time_limit' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:1|max:100',
        ]);

        $quiz->update($validated);

        return redirect()->route('admin.quizzes')
            ->with('status', 'Quiz mis à jour avec succès.');
    }

    /**
     * Delete the quiz
     */
    public function destroy(Quiz $quiz)
    {
        $quiz->delete();

        return redirect()->route('admin.quizzes')
            ->with('status', 'Quiz supprimé avec succès.');
    }

    /**
     * Show the question management page
     */
    public function questions(Quiz $quiz)
    {
        return view('admin.quizzes.questions', compact('quiz'));
    }

    /**
     * Add a question to the quiz
     */
    public function storeQuestion(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_answer' => 'required|in:A,B,C,D',
            'points' => 'required|integer|min:1',
        ]);

        // Convert options to JSON format
        $options = [
            'A' => $validated['option_a'],
            'B' => $validated['option_b'],
            'C' => $validated['option_c'],
            'D' => $validated['option_d'],
        ];

        Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => $validated['question_text'],
            'options' => json_encode($options),
            'correct_answer' => $validated['correct_answer'],
            'points' => $validated['points'],
        ]);

        return redirect()->route('admin.quizzes.questions', $quiz)
            ->with('status', 'Question ajoutée avec succès.');
    }

    /**
     * Delete a question
     */
    public function destroyQuestion(Question $question)
    {
        $quizId = $question->quiz_id;
        $question->delete();

        return redirect()->route('admin.quizzes.questions', $quizId)
            ->with('status', 'Question supprimée avec succès.');
    }
}
