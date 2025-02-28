<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminQuizController extends Controller
{
    /**
     * Show the quiz creation form
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
