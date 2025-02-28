<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && $request->user()->status !== 'active') {
            if ($request->user()->status === 'pending') {
                return redirect()->route('status.pending');
            }

            if ($request->user()->status === 'rejected') {
                return redirect()->route('status.rejected');
            }
        }

        return $next($request);
    }
}
