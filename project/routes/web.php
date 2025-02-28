<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminQuizController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Status routes - accessible when logged in
Route::middleware(['auth'])->group(function () {
    Route::get('/pending', [StatusController::class, 'pending'])->name('status.pending');
    Route::get('/rejected', [StatusController::class, 'rejected'])->name('status.rejected');
});

// Dashboard route
Route::get('/dashboard', function () {
    // If user is logged in, redirect to home controller
    if (auth()->check()) {
        return redirect()->route('home');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Home controller handles role-based routing
Route::get('/home', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('home');

// Profile routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/upload-id', [ProfileController::class, 'uploadId'])->name('profile.upload-id');
});

// Admin routes
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// Staff routes
Route::middleware(['auth', 'verified', 'role:staff'])->group(function () {
    Route::get('/staff/dashboard', function () {
        return view('staff.dashboard');
    })->name('staff.dashboard');
});

// Candidate routes with profile completion check
// Quiz routes - remove profile.complete middleware temporarily for testing
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/quiz', [QuizController::class, 'index'])->name('quiz.index');
    Route::post('/quiz/{quiz}/start', [QuizController::class, 'start'])->name('quiz.start');
    Route::get('/quiz/take/{userQuiz}', [QuizController::class, 'take'])->name('quiz.take');
    Route::post('/quiz/submit/{userQuiz}', [QuizController::class, 'submit'])->name('quiz.submit');
    Route::get('/quiz/results/{userQuiz}', [QuizController::class, 'results'])->name('quiz.results');
});

// Assessment status route
Route::get('/assessment-status', [AssessmentController::class, 'status'])
    ->middleware(['auth', 'verified'])
    ->name('assessment.status');

// Routes Admin
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/candidates', [AdminController::class, 'candidates'])->name('candidates');
    Route::get('/candidates/{user}', [AdminController::class, 'candidateDetails'])->name('candidate.details');

    Route::get('/appointments', [AdminController::class, 'appointments'])->name('appointments');
    Route::post('/appointments', [AdminController::class, 'storeAppointment'])->name('appointments.store');
    Route::put('/appointments/{appointment}', [AdminController::class, 'updateAppointment'])->name('appointments.update');

    Route::get('/quizzes', [AdminController::class, 'quizzes'])->name('quizzes');
    Route::post('/quizzes/{quiz}/toggle', [AdminController::class, 'toggleQuizStatus'])->name('quizzes.toggle');

    // Quiz management routes
    Route::get('/quizzes/create', [AdminQuizController::class, 'create'])->name('quizzes.create');
    Route::post('/quizzes', [AdminQuizController::class, 'store'])->name('quizzes.store');
    Route::get('/quizzes/{quiz}/edit', [AdminQuizController::class, 'edit'])->name('quizzes.edit');
    Route::put('/quizzes/{quiz}', [AdminQuizController::class, 'update'])->name('quizzes.update');
    Route::delete('/quizzes/{quiz}', [AdminQuizController::class, 'destroy'])->name('quizzes.destroy');

    // Quiz questions management
    Route::get('/quizzes/{quiz}/questions', [AdminQuizController::class, 'questions'])->name('quizzes.questions');
    Route::post('/quizzes/{quiz}/questions', [AdminQuizController::class, 'storeQuestion'])->name('quizzes.questions.store');
    Route::delete('/questions/{question}', [AdminQuizController::class, 'destroyQuestion'])->name('quizzes.questions.destroy');
});

require __DIR__.'/auth.php';
