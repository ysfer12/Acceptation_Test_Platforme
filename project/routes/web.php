<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminQuizController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\StaffAvailabilityController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Auth routes are provided by Laravel Breeze and are already registered

// Dashboard route
Route::get('/dashboard', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/upload-id', [ProfileController::class, 'uploadId'])->name('profile.upload.id');
});

// Status routes
Route::middleware(['auth'])->group(function () {
    Route::get('/status/pending', [StatusController::class, 'pending'])->name('status.pending');
    Route::get('/status/rejected', [StatusController::class, 'rejected'])->name('status.rejected');
});

// Candidate routes
Route::middleware(['auth', 'verified', 'check.user.status'])->group(function () {
    // Quiz routes
    Route::middleware(['check.active.quiz'])->group(function () {
        Route::get('/quiz', [QuizController::class, 'index'])->name('quiz.index');
        Route::post('/quiz/{quiz}/start', [QuizController::class, 'start'])->name('quiz.start');
    });
    
    // Quiz taking routes (no active quiz check since user already started the quiz)
    Route::get('/quiz/{userQuiz}', [QuizController::class, 'take'])->name('quiz.take');
    Route::post('/quiz/{userQuiz}/submit', [QuizController::class, 'submit'])->name('quiz.submit');
    Route::get('/quiz/{userQuiz}/results', [QuizController::class, 'results'])->name('quiz.results');
    Route::post('/quiz/save-progress', [QuizController::class, 'saveProgress'])->name('quiz.save.progress');
    
    // Assessment routes
    Route::get('/assessment/status', [AssessmentController::class, 'status'])->name('assessment.status');
});

// Admin routes
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Candidates management
    Route::get('/candidates', [AdminController::class, 'candidates'])->name('candidates');
    Route::get('/candidates/{user}', [AdminController::class, 'candidateDetails'])->name('candidate.details');
    
    // Appointments management
    Route::get('/appointments', [AdminController::class, 'appointments'])->name('appointments');
    Route::post('/appointments', [AdminController::class, 'storeAppointment'])->name('appointments.store');
    Route::put('/appointments/{appointment}', [AdminController::class, 'updateAppointment'])->name('appointments.update');
    Route::post('/candidates/{candidate}/assign-staff', [AdminController::class, 'assignStaff'])->name('candidates.assign.staff');
    
    // Auto-assignment route
    Route::post('/auto-assign-candidates', [AdminController::class, 'autoAssignCandidates'])->name('auto.assign.candidates');
    
    // Quiz management
    Route::get('/quizzes', [AdminController::class, 'quizzes'])->name('quizzes');
    Route::post('/quizzes/{quiz}/toggle', [AdminController::class, 'toggleQuizStatus'])->name('quizzes.toggle');
    
    // Quiz CRUD routes
    Route::get('/quizzes/create', [AdminQuizController::class, 'create'])->name('quizzes.create');
    Route::post('/quizzes', [AdminQuizController::class, 'store'])->name('quizzes.store');
    Route::get('/quizzes/{quiz}/edit', [AdminQuizController::class, 'edit'])->name('quizzes.edit');
    Route::put('/quizzes/{quiz}', [AdminQuizController::class, 'update'])->name('quizzes.update');
    Route::delete('/quizzes/{quiz}', [AdminQuizController::class, 'destroy'])->name('quizzes.destroy');
    
    // Questions management
    Route::get('/quizzes/{quiz}/questions', [AdminQuizController::class, 'questions'])->name('quizzes.questions');
    Route::post('/quizzes/{quiz}/questions', [AdminQuizController::class, 'storeQuestion'])->name('quizzes.questions.store');
    Route::delete('/questions/{question}', [AdminQuizController::class, 'destroyQuestion'])->name('quizzes.questions.destroy');
    Route::get('/questions/{question}/edit', [AdminQuizController::class, 'editQuestion'])->name('quizzes.questions.edit');
    Route::put('/questions/{question}', [AdminQuizController::class, 'updateQuestion'])->name('quizzes.questions.update');
    
    // Quiz results and analytics
    Route::get('/quiz-results', [AdminController::class, 'quizResults'])->name('quiz.results');
    Route::get('/quiz-results/{userQuiz}', [AdminController::class, 'quizResultDetails'])->name('quiz.results.details');
    
    // Evaluations
    Route::get('/evaluations', [AdminController::class, 'evaluations'])->name('evaluations');
    Route::get('/evaluations/{evaluation}', [AdminController::class, 'evaluationDetails'])->name('evaluation.details');
});

// Staff routes
Route::middleware(['auth', 'verified', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [StaffController::class, 'dashboard'])->name('dashboard');
    Route::get('/calendar', [StaffController::class, 'calendar'])->name('calendar');
    Route::get('/appointments/date', [StaffController::class, 'appointmentsByDate'])->name('appointments.by.date');
    Route::post('/appointments/{appointment}/complete', [StaffController::class, 'completeAppointment'])->name('appointment.complete');
    Route::post('/appointments/{appointment}/notes', [StaffController::class, 'updateNotes'])->name('appointment.notes');
    Route::get('/appointments/{appointment}/notes', [StaffController::class, 'getNotes'])->name('appointment.get.notes');
    
    // Enhanced evaluation system
    Route::get('/evaluation/form', [StaffController::class, 'evaluationForm'])->name('evaluation.form');
    Route::get('/evaluation/create/{candidate}/{appointment?}', [StaffController::class, 'evaluationCreate'])->name('evaluation.create');
    Route::post('/evaluation', [StaffController::class, 'storeEvaluation'])->name('evaluation.store');
    Route::get('/evaluation/{evaluation}/edit', [StaffController::class, 'editEvaluation'])->name('evaluation.edit');
    Route::put('/evaluation/{evaluation}', [StaffController::class, 'updateEvaluation'])->name('evaluation.update');
    
    // Candidate profile
    Route::get('/candidates/{user}', [StaffController::class, 'candidateProfile'])->name('candidate.profile');
    
    // Staff availability management
    Route::get('/availability', [StaffAvailabilityController::class, 'index'])->name('availability');
    Route::post('/availability', [StaffAvailabilityController::class, 'store'])->name('availability.store');
    Route::delete('/availability/{availability}', [StaffAvailabilityController::class, 'destroy'])->name('availability.destroy');
    
    // Search and filter candidates
    Route::get('/candidates', [StaffController::class, 'candidates'])->name('candidates');
    Route::get('/candidates/search', [StaffController::class, 'searchCandidates'])->name('candidates.search');
    
    // Appointments management
    Route::get('/appointments', [StaffController::class, 'appointments'])->name('appointments');
});

// API routes for dashboard updates and dynamic content
Route::middleware(['auth'])->prefix('api')->group(function() {
    Route::get('/dashboard/stats', [HomeController::class, 'dashboardStats']);
    Route::get('/quiz/status', [QuizController::class, 'apiStatus']);
    Route::get('/appointments/upcoming', [HomeController::class, 'upcomingAppointments']);
});

require __DIR__.'/auth.php';