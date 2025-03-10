<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserQuiz;
use App\Models\Appointment;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StaffController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'role:staff']);
    }

    /**
     * Display the staff dashboard.
     */
    public function dashboard()
    {
        $staffId = auth()->id();
        $today = Carbon::today();

        // Get appointment statistics
        $appointmentStats = [
            'today' => Appointment::where('staff_id', $staffId)
                ->whereDate('scheduled_at', $today)
                ->count(),
            'pending' => Appointment::where('staff_id', $staffId)
                ->where('status', 'scheduled')
                ->where('scheduled_at', '>', $today)
                ->count(),
            'completed' => Appointment::where('staff_id', $staffId)
                ->where('status', 'completed')
                ->count(),
        ];

        // Calculate profile completion percentage
        $user = auth()->user();
        $fields = ['first_name', 'last_name', 'phone', 'address'];
        $filledFields = 0;

        foreach ($fields as $field) {
            if (!empty($user->$field)) {
                $filledFields++;
            }
        }

        $profileCompletion = round(($filledFields / count($fields)) * 100);

        // Get today's appointments
        $todayAppointments = Appointment::with('user')
            ->where('staff_id', $staffId)
            ->whereDate('scheduled_at', $today)
            ->orderBy('scheduled_at', 'asc')
            ->get();

        // Get upcoming appointments
        $upcomingAppointments = Appointment::with('user')
            ->where('staff_id', $staffId)
            ->where('status', 'scheduled')
            ->where('scheduled_at', '>', $today)
            ->orderBy('scheduled_at', 'asc')
            ->limit(5)
            ->get();

        // Get recent evaluations
        $recentEvaluations = Evaluation::with('user')
            ->where('staff_id', $staffId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('staff.dashboard', compact(
            'appointmentStats',
            'profileCompletion',
            'todayAppointments',
            'upcomingAppointments',
            'recentEvaluations'
        ));
    }

    /**
     * Display the staff calendar.
     */
    public function calendar()
    {
        $staffId = auth()->id();

        // Get all upcoming appointments for this staff member
        $appointments = Appointment::with('user')
            ->where('staff_id', $staffId)
            ->where('scheduled_at', '>=', Carbon::today())
            ->orderBy('scheduled_at', 'asc')
            ->get();

        return view('staff.calendar', compact('appointments'));
    }

    /**
     * Get appointments for a specific date.
     */
    public function appointmentsByDate(Request $request)
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
        ]);

        $staffId = auth()->id();
        $date = $request->date;

        $appointments = Appointment::with('user')
            ->where('staff_id', $staffId)
            ->whereDate('scheduled_at', $date)
            ->orderBy('scheduled_at', 'asc')
            ->get();

        return response()->json([
            'appointments' => $appointments,
            'date' => $date,
        ]);
    }

/**
 * Display all appointments for a staff member.
 */
public function appointments()
{
    $staffId = auth()->id();
    $today = Carbon::today();
    
    $appointmentsByDate = Appointment::with('user')
        ->where('staff_id', $staffId)
        ->where('scheduled_at', '>=', $today)
        ->orderBy('scheduled_at')
        ->get()
        ->groupBy(function($appointment) {
            return $appointment->scheduled_at->format('Y-m-d');
        });
        
    return view('staff.appointments', compact('appointmentsByDate'));
}
    /**
     * Mark an appointment as completed.
     */
    public function completeAppointment(Appointment $appointment)
    {
        // Ensure the appointment belongs to the authenticated staff member
        if ($appointment->staff_id !== auth()->id()) {
            return redirect()->route('staff.dashboard')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier ce rendez-vous.');
        }

        $appointment->status = 'completed';
        $appointment->save();

        return redirect()->route('staff.dashboard')
            ->with('status', 'Rendez-vous marqué comme terminé avec succès.');
    }

    /**
     * Update appointment notes.
     */
    public function updateNotes(Request $request, Appointment $appointment)
    {
        // Ensure the appointment belongs to the authenticated staff member
        if ($appointment->staff_id !== auth()->id()) {
            return redirect()->route('staff.dashboard')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier ce rendez-vous.');
        }

        $request->validate([
            'notes' => 'nullable|string',
        ]);

        $appointment->notes = $request->notes;
        $appointment->save();

        return redirect()->route('staff.dashboard')
            ->with('status', 'Notes mises à jour avec succès.');
    }

    /**
     * Get appointment notes.
     */
    public function getNotes(Appointment $appointment)
    {
        // Ensure the appointment belongs to the authenticated staff member
        if ($appointment->staff_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'notes' => $appointment->notes,
        ]);
    }

    /**
     * Display the evaluation form.
     */
    public function evaluationForm()
    {
        $staffId = auth()->id();

        // Get all candidates that have completed the quiz
        $candidates = User::where('role', 'candidate')
            ->whereHas('userQuizzes', function($query) {
                $query->whereNotNull('completed_at');
            })
            ->get();

        return view('staff.evaluation-form', compact('candidates'));
    }

    /**
     * Store a new evaluation.
     */
// app/Http/Controllers/StaffController.php - Update method

    /**
     * Display the candidate profile.
     */
    public function candidateProfile(User $user)
    {
        // Ensure the user is a candidate
        if ($user->role !== 'candidate') {
            return redirect()->route('staff.dashboard')
                ->with('error', 'Utilisateur non trouvé.');
        }

        $quizResults = UserQuiz::where('user_id', $user->id)
            ->orderBy('completed_at', 'desc')
            ->get();

        $appointments = Appointment::where('user_id', $user->id)
            ->orderBy('scheduled_at', 'desc')
            ->get();

        $evaluations = Evaluation::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('staff.candidate-profile', compact('user', 'quizResults', 'appointments', 'evaluations'));
    }

    public function evaluationCreate($candidateId, $appointmentId = null)
    {
        $candidate = User::findOrFail($candidateId);
        $appointment = null;
        
        if ($appointmentId) {
            $appointment = Appointment::findOrFail($appointmentId);
            // Check if appointment belongs to the staff member
            if ($appointment->staff_id !== auth()->id()) {
                return redirect()->route('staff.dashboard')
                    ->with('error', 'You are not authorized to evaluate this appointment.');
            }
        }
        
        return view('staff.evaluation-create', compact('candidate', 'appointment'));
    }

    public function storeEvaluation(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'type' => 'required|in:interview,technical,soft_skills,overall',
            'result' => 'required|in:pass,fail,pending',
            'score' => 'nullable|integer|min:0|max:100',
            'criteria' => 'nullable|array',
            'strengths' => 'nullable|string',
            'weaknesses' => 'nullable|string',
            'comments' => 'nullable|string',
        ]);
        
        // Create evaluation
        $evaluation = Evaluation::create([
            'user_id' => $validated['user_id'],
            'staff_id' => auth()->id(),
            'appointment_id' => $validated['appointment_id'],
            'type' => $validated['type'],
            'result' => $validated['result'],
            'score' => $validated['score'],
            'criteria_scores' => $request->criteria,
            'strengths' => $validated['strengths'],
            'weaknesses' => $validated['weaknesses'],
            'comments' => $validated['comments'],
        ]);
        
        // If this is for an appointment, update the appointment status
        if ($validated['appointment_id']) {
            $appointment = Appointment::find($validated['appointment_id']);
            if ($appointment && $appointment->staff_id === auth()->id()) {
                $appointment->status = 'completed';
                $appointment->save();
            }
        }
        
        return redirect()->route('staff.candidate.profile', $validated['user_id'])
            ->with('status', 'Evaluation submitted successfully.');
    }
    public function editEvaluation(Evaluation $evaluation)
{
    // Check if evaluation belongs to the staff member
    if ($evaluation->staff_id !== auth()->id()) {
        return redirect()->route('staff.dashboard')
            ->with('error', 'You are not authorized to edit this evaluation.');
    }
    
    $candidate = User::find($evaluation->user_id);
    $appointment = $evaluation->appointment;
    
    return view('staff.evaluation-edit', compact('evaluation', 'candidate', 'appointment'));
}
public function updateEvaluation(Request $request, Evaluation $evaluation)
{
    // Check if evaluation belongs to the staff member
    if ($evaluation->staff_id !== auth()->id()) {
        return redirect()->route('staff.dashboard')
            ->with('error', 'You are not authorized to update this evaluation.');
    }
    
    $validated = $request->validate([
        'type' => 'required|in:interview,technical,soft_skills,overall',
        'result' => 'required|in:pass,fail,pending',
        'score' => 'nullable|integer|min:0|max:100',
        'criteria' => 'nullable|array',
        'strengths' => 'nullable|string',
        'weaknesses' => 'nullable|string',
        'comments' => 'nullable|string',
    ]);
    
    // Update evaluation
    $evaluation->update([
        'type' => $validated['type'],
        'result' => $validated['result'],
        'score' => $validated['score'],
        'criteria_scores' => $request->criteria,
        'strengths' => $validated['strengths'],
        'weaknesses' => $validated['weaknesses'],
        'comments' => $validated['comments'],
    ]);
    
    return redirect()->route('staff.candidate.profile', $evaluation->user_id)
        ->with('status', 'Evaluation updated successfully.');
}

/**
 * Search and filter candidates.
 */
public function searchCandidates(Request $request)
{
    $query = User::where('role', 'candidate');
    
    // Apply filters based on request
    if ($request->has('filter')) {
        $filter = $request->filter;
        
        if ($filter === 'passed_quiz') {
            $query->whereHas('userQuizzes', function($q) {
                $q->where('passed', true);
            });
        } elseif ($filter === 'pending_evaluation') {
            $query->whereHas('appointments', function($q) {
                $q->where('status', 'completed')
                  ->whereDoesntHave('evaluations', function($e) {
                      $e->where('staff_id', auth()->id());
                  });
            });
        }
    }
    
    // Apply search term if present
    if ($request->has('search') && !empty($request->search)) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }
    
    $candidates = $query->paginate(10);
    
    return view('staff.candidates', compact('candidates'));
}
}
