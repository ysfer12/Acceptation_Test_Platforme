<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UploadIdRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Upload the user's ID card.
     */
    public function uploadId(UploadIdRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($request->hasFile('id_card')) {
            $path = $request->file('id_card')->store('id_cards', 'public');
            $user->id_card_path = $path;
            $user->save();

            // Check if all required fields are filled
            $requiredFields = ['first_name', 'last_name', 'birth_date', 'phone', 'address', 'id_card_path'];
            $profileComplete = true;

            foreach ($requiredFields as $field) {
                if (empty($user->$field)) {
                    $profileComplete = false;
                    break;
                }
            }

            // If profile is complete, redirect to quiz
            if ($profileComplete) {
                return redirect()->route('quiz.index')
                    ->with('message', 'Your profile is complete. You can now take the quiz!');
            }
        }

        return Redirect::route('profile.edit')->with('status', 'id-card-uploaded');
    }
}
