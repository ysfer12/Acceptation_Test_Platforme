<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\Question;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a sample quiz
        $quiz = Quiz::create([
            'title' => 'Recruitment Assessment',
            'description' => 'This assessment evaluates your skills and knowledge for the position.',
            'time_limit' => 30, // 30 minutes
            'passing_score' => 70, // 70%
            'is_active' => true,
        ]);

        // Add some sample questions
        $questions = [
            [
                'question_text' => 'What is the capital of France?',
                'options' => json_encode(['A' => 'London', 'B' => 'Berlin', 'C' => 'Paris', 'D' => 'Madrid']),
                'correct_answer' => 'C',
                'points' => 1,
            ],
            [
                'question_text' => 'Which programming language is known for web development?',
                'options' => json_encode(['A' => 'Java', 'B' => 'C++', 'C' => 'Python', 'D' => 'JavaScript']),
                'correct_answer' => 'D',
                'points' => 1,
            ],
            [
                'question_text' => 'What does HTML stand for?',
                'options' => json_encode([
                    'A' => 'Hyper Text Markup Language',
                    'B' => 'High Tech Modern Language',
                    'C' => 'Hyper Transfer Method Language',
                    'D' => 'Home Tool Markup Language'
                ]),
                'correct_answer' => 'A',
                'points' => 1,
            ],
            [
                'question_text' => 'Which of the following is not a database management system?',
                'options' => json_encode(['A' => 'MySQL', 'B' => 'MongoDB', 'C' => 'Laravel', 'D' => 'PostgreSQL']),
                'correct_answer' => 'C',
                'points' => 1,
            ],
            [
                'question_text' => 'What is the correct way to define a variable in JavaScript?',
                'options' => json_encode(['A' => 'var myVar = 5;', 'B' => 'variable myVar = 5;', 'C' => 'v myVar = 5;', 'D' => '$myVar = 5;']),
                'correct_answer' => 'A',
                'points' => 1,
            ]
        ];

        foreach ($questions as $q) {
            $q['quiz_id'] = $quiz->id;
            Question::create($q);
        }
    }
}
