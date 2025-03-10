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
        // Get the active quiz (set by admin)
        $quiz = Quiz::where('is_active', true)->first();
    
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
        $questions = $quiz->questions;
    
        // If the quiz has no questions, redirect back with an error
        if ($questions->count() === 0) {
            return redirect()->route('quiz.index')
                ->with('error', 'This quiz has no questions yet. Please try again later.');
        }
    
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
        
        // Calculate score based on correct answers
        $totalPoints = $questions->sum('points');
        $earnedPoints = 0;
        
        foreach ($questions as $question) {
            $questionId = $question->id;
            $userAnswer = $answers[$questionId] ?? null;
            
            if ($userAnswer === $question->correct_answer) {
                $earnedPoints += $question->points;
            }
        }
        
        // Calculate percentage score
        $score = $totalPoints > 0 ? round(($earnedPoints / $totalPoints) * 100) : 0;
        
        // Update user quiz
        $userQuiz->score = $score;
        $userQuiz->answers = json_encode($answers);
        $userQuiz->completed_at = now();
        $userQuiz->passed = $score >= $quiz->passing_score;
        $userQuiz->time_taken = now()->diffInSeconds($userQuiz->started_at);
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
