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
    $appointment = Appointment::with('staff')
        ->where('user_id', $user->id)
        ->orderBy('scheduled_at', 'desc')
        ->first();
        
    // Get evaluations
    $evaluations = Evaluation::where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->get();
        
    // Overall evaluation status
    $overallEvaluation = $evaluations->where('type', 'overall')->first();

    return view('assessment.status', compact('latestQuiz', 'appointment', 'evaluations', 'overallEvaluation'));
}}
