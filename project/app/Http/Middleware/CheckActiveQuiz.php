<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Quiz;
use Illuminate\Http\Request;

class CheckActiveQuiz
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $activeQuiz = Quiz::where('is_active', true)->first();
        
        if (!$activeQuiz) {
            return redirect()->route('dashboard')
                ->with('error', 'No active quiz is currently available. Please check back later.');
        }
        
        if ($activeQuiz->questions()->count() === 0) {
            return redirect()->route('dashboard')
                ->with('error', 'The active quiz has no questions. Please check back later.');
        }
        
        return $next($request);
    }
}