<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserQuiz;
use App\Models\Appointment;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\AppointmentAssignmentService;


class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard()
    {
        // Retrieve statistics
        $stats = [
            'totalCandidates' => User::where('role', 'candidate')->count(),
            'pendingProfiles' => User::where('role', 'candidate')
                ->where(function($query) {
                    $query->whereNull('id_card_path')
                        ->orWhereNull('first_name')
                        ->orWhereNull('last_name')
                        ->orWhereNull('birth_date')
                        ->orWhereNull('phone')
                        ->orWhereNull('address');
                })
                ->count(),
            'completedQuizzes' => UserQuiz::whereNotNull('completed_at')->count(),
            'passedQuizzes' => UserQuiz::where('passed', true)->count(),
            'pendingAppointments' => Appointment::where('status', 'scheduled')->count() ?? 0,
            'activeQuiz' => Quiz::where('is_active', true)->first(),
            'quizCount' => Quiz::count(),
            'totalQuestions' => DB::table('questions')->count(),
            'avgQuizScore' => UserQuiz::whereNotNull('completed_at')->avg('score'),
        ];

        // Recent candidates
        $recentCandidates = User::where('role', 'candidate')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Recent quiz results
        $recentQuizResults = UserQuiz::with('user')
            ->whereNotNull('completed_at')
            ->orderBy('completed_at', 'desc')
            ->take(5)
            ->get();

        // Upcoming appointments
        $upcomingAppointments = Appointment::with(['user', 'staff'])
            ->where('status', 'scheduled')
            ->where('scheduled_at', '>', now())
            ->orderBy('scheduled_at', 'asc')
            ->take(5)
            ->get() ?? collect([]);

        return view('admin.dashboard', compact(
            'stats',
            'recentCandidates',
            'recentQuizResults',
            'upcomingAppointments'
        ));
    }

    public function assignStaff(Request $request, $candidateId)
{
    $request->validate([
        'staff_id' => 'required|exists:users,id',
        'scheduled_at' => 'required|date|after:now',
        'location' => 'required|string',
    ]);
    
    $candidate = User::findOrFail($candidateId);
    
    // Check if candidate has passed the quiz
    $passedQuiz = UserQuiz::where('user_id', $candidateId)
        ->where('passed', true)
        ->exists();
        
    if (!$passedQuiz) {
        return back()->with('error', 'Candidate must pass the online assessment first.');
    }
    
    // Create or update appointment
    Appointment::updateOrCreate(
        ['user_id' => $candidateId, 'status' => 'scheduled'],
        [
            'staff_id' => $request->staff_id,
            'scheduled_at' => $request->scheduled_at,
            'location' => $request->location,
            'notes' => $request->notes,
        ]
    );
    
    return back()->with('status', 'Appointment scheduled successfully.');
}
    /**
     * Afficher la liste des candidats
     */
    public function candidates()
    {
        $candidates = User::where('role', 'candidate')
            ->withCount(['userQuizzes as has_passed_quiz' => function ($query) {
                $query->where('passed', true);
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.candidates', compact('candidates'));
    }

    /**
     * Afficher les détails d'un candidat
     */
    public function candidateDetails(User $user)
    {
        if ($user->role !== 'candidate') {
            return redirect()->route('admin.candidates')
                ->with('error', 'Utilisateur non trouvé.');
        }

        $quizResults = UserQuiz::where('user_id', $user->id)
            ->orderBy('completed_at', 'desc')
            ->get();

        $appointments = Appointment::where('user_id', $user->id)
            ->orderBy('scheduled_at', 'desc')
            ->get();

        return view('admin.candidate-details', compact('user', 'quizResults', 'appointments'));
    }

    /**
     * Afficher et gérer les rendez-vous
     */
    public function appointments()
    {
        $appointments = Appointment::with(['user', 'staff'])
            ->orderBy('scheduled_at', 'desc')
            ->paginate(15);

        $staffMembers = User::where('role', 'staff')->get();

        return view('admin.appointments', compact('appointments', 'staffMembers'));
    }

    /**
     * Créer un nouveau rendez-vous
     */
    public function storeAppointment(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'staff_id' => 'required|exists:users,id',
            'scheduled_at' => 'required|date|after:now',
            'location' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        Appointment::create($validated);

        return redirect()->route('admin.appointments')
            ->with('status', 'Rendez-vous créé avec succès.');
    }

    /**
     * Mettre à jour un rendez-vous
     */
    public function updateAppointment(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:users,id',
            'scheduled_at' => 'required|date',
            'location' => 'required|string|max:255',
            'status' => 'required|in:scheduled,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $appointment->update($validated);

        return redirect()->route('admin.appointments')
            ->with('status', 'Rendez-vous mis à jour avec succès.');
    }

    /**
     * Gérer les quiz
     */
    public function quizzes()
    {
        $quizzes = Quiz::withCount('questions')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.quizzes', compact('quizzes'));
    }

    /**
     * Changer le statut d'activation d'un quiz
     */
    public function toggleQuizStatus(Quiz $quiz)
    {
        $quiz->is_active = !$quiz->is_active;
        $quiz->save();
        
        // If activating this quiz, deactivate all others
        if ($quiz->is_active) {
            Quiz::where('id', '!=', $quiz->id)->update(['is_active' => false]);
        }
        
        return redirect()->route('admin.quizzes')
            ->with('status', 'Quiz status updated successfully.');
    }

    /**
 * Automatically assign candidates to staff
 */
public function autoAssignCandidates(Request $request)
{
    try {
        // Create service manually
        $service = new \App\Services\AppointmentAssignmentService();
        
        // Get assignments
        $assignments = $service->autoAssignCandidates();
        
        // For debugging - temporarily return a simple response instead of redirecting
        return response()->json([
            'success' => true,
            'assignments' => count($assignments),
            'message' => count($assignments) > 0 ? 
                         count($assignments) . ' candidates assigned successfully.' : 
                         'No candidates could be assigned.'
        ]);
        
        // Original redirect code (commented out for debugging)
        /*
        if (count($assignments) > 0) {
            return redirect()->route('admin.appointments')
                ->with('status', count($assignments) . ' candidates have been successfully assigned to staff members.');
        }
        
        return redirect()->route('admin.appointments')
            ->with('error', 'No candidates could be assigned at this time. Please check staff availability.');
        */
    } catch (\Exception $e) {
        // Return error details for debugging
        return response()->json([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
}
}
