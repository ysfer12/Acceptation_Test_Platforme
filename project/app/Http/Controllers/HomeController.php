<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserQuiz;
use App\Models\Appointment;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isCandidate()) {
            // Check if profile is complete
            $requiredFields = ['first_name', 'last_name', 'birth_date', 'phone', 'address', 'id_card_path'];
            $profileComplete = true;

            foreach ($requiredFields as $field) {
                if (empty($user->$field)) {
                    $profileComplete = false;
                    break;
                }
            }

            // If profile is not complete, redirect to profile edit
            if (!$profileComplete) {
                return redirect()->route('profile.edit')
                    ->with('message', 'Please complete your profile before proceeding to the quiz.');
            }

            // Check if user has already taken the quiz
            $completedQuiz = UserQuiz::where('user_id', $user->id)
                ->whereNotNull('completed_at')
                ->first();

            if ($completedQuiz) {
                // If quiz is completed, show assessment status
                return redirect()->route('assessment.status');
            } else {
                // If profile is complete but quiz not taken, redirect to quiz
                return redirect()->route('quiz.index');
            }
        } elseif ($user->isStaff()) {
            return view('staff.dashboard');
        } elseif ($user->isAdmin()) {
            return view('admin.dashboard');
        }

        return view('dashboard');
    }
}
