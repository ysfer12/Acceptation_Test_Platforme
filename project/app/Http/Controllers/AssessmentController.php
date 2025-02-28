<?php

namespace App\Http\Controllers;

use App\Models\UserQuiz;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    /**
     * Show assessment status page
     */
    public function status()
    {
        $user = auth()->user();

        // Get latest quiz result
        $latestQuiz = UserQuiz::where('user_id', $user->id)
            ->orderBy('completed_at', 'desc')
            ->first();

        // Get scheduled appointment if any
        $appointment = Appointment::where('user_id', $user->id)
            ->orderBy('scheduled_at', 'desc')
            ->first();

        return view('assessment.status', compact('latestQuiz', 'appointment'));
    }
}
