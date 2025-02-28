<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'time_limit',
        'passing_score',
        'is_active',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function userQuizzes()
    {
        return $this->hasMany(UserQuiz::class);
    }
}
