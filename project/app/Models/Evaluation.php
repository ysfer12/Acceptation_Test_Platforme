<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'staff_id',
        'appointment_id',
        'type',
        'result',
        'score',
        'criteria_scores',
        'strengths',
        'weaknesses',
        'comments',
    ];

    protected $casts = [
        'criteria_scores' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}