<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProfileComplete extends Middleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->isCandidate()) {
            // Check if any required profile fields are empty
            $user = $request->user();
            $requiredFields = ['first_name', 'last_name', 'birth_date', 'phone', 'address', 'id_card_path'];
            $missingFields = [];

            foreach ($requiredFields as $field) {
                if (empty($user->$field)) {
                    $missingFields[] = str_replace('_', ' ', $field);
                }
            }

            if (!empty($missingFields)) {
                $fieldList = implode(', ', $missingFields);

                return redirect()->route('profile.edit')
                    ->with('message', "Please complete your profile before proceeding. Missing information: {$fieldList}.");
            }
        }

        return $next($request);
    }
}
