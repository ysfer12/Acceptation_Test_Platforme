<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_id',
        'score',
        'time_taken',
        'answers',
        'started_at',
        'completed_at',
        'passed',
    ];

    protected $casts = [
        'answers' => 'json',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'passed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
