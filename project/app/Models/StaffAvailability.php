<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'day_of_week', // 0-6 (Sunday-Saturday)
        'start_time',  // Time format (HH:MM:SS)
        'end_time',    // Time format (HH:MM:SS)
        'is_recurring', // Boolean
        'date',        // For non-recurring availability
    ];

    protected $casts = [
        'is_recurring' => 'boolean',
        'date' => 'date',
    ];

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}